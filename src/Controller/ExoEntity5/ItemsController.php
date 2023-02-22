<?php
namespace App\Controller\ExoEntity5;

use App\Entity\Category;
use App\Entity\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemsController extends AbstractController
{
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        // Affichage liste des items
        $repository = $doctrine->getRepository(Item::class);
        $items = $repository->findByLabelAndMinimalQty(
            $request->query->get('search_list') ?: '',
            $request->query->get('qty_minimal') ?: 0,
        );

        return $this->render('exo_entity_5/items/index.html.twig', [
            'items' => $items,
        ]);
    }

    public function create(ManagerRegistry $doctrine): Response
    {
        // Affichage vue formulaire creation item
        $categories = $doctrine->getManager()
            ->getRepository(Category::class)
            ->findBy([
                'enable' => true,
            ]);

        return $this->render('exo_entity_5/items/create.html.twig', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request, ManagerRegistry $doctrine): Response
    {
        // Action formulaire creation item
        
        // Entity Manager
        $entityManager = $doctrine->getManager();

        $category = $entityManager->getRepository(Category::class)
            ->find($request->request->get('category_id'));

        if (empty($category)) {
            $this->addFlash('error', 'The category is not found !');
            return $this->redirectToRoute('entity5_items_create');
        }

        $item = new Item();
        $item->setLabel($request->request->get('label'));
        $item->setPrice($request->request->get('price'));
        $item->setQuantity($request->request->get('quantity'));
        $item->setLocation($request->request->get('location'));
        $item->setCreatedAt(new \DateTime);
        $item->setCategory($category);
        $entityManager->persist($item);
        $entityManager->flush();

        $this->addFlash('success', 'Item created !');
        return $this->redirectToRoute('entity5_items_index');
    }

    public function attachCategory(ManagerRegistry $doctrine, $id, $category_id)
    {
        // Entity Manager
        $entityManager = $doctrine->getManager();

        $category = $entityManager->getRepository(Category::class)->find($category_id);
        $item = $entityManager->getRepository(Item::class)->find($id);

        if (empty($category) or empty($item)) {
            $this->addFlash('error', 'The category or item is not found !');
            return $this->redirectToRoute('entity5_items_index');
        }

        // Possibilité 1: Depuis Category
//        $category->addItem($item);
//        $entityManager->persist($category);

        // OU Possibilité 2:  Depuis Item
        $item->setCategory($category);
        $entityManager->persist($item);

        $entityManager->flush();
    }

    public function detachCategory(ManagerRegistry $doctrine, $id)
    {
        // Entity Manager
        $entityManager = $doctrine->getManager();

        $item = $entityManager->getRepository(Item::class)->find($id);

        if (empty($item)) {
            $this->addFlash('error', 'The item is not found !');
            return $this->redirectToRoute('entity5_items_index');
        }

        $item->setCategory(null);
        $entityManager->persist($item);

        $entityManager->flush();
    }
}
