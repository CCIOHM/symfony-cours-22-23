<?php
namespace App\Controller\ExoEntity3;

use App\Entity\Item;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemsController extends AbstractController
{
    public function index(ManagerRegistry $doctrine): Response
    {
        // Affichage liste des items
        $repository = $doctrine->getRepository(Item::class);
        $items = $repository->findAll();

        return $this->render('exo_entity_3/items/index.html.twig', [
            'items' => $items,
        ]);
    }

    public function create(): Response
    {
        // Affichage vue formulaire creation item
        return $this->render('exo_entity_3/items/create.html.twig');
    }

    public function store(Request $request, ManagerRegistry $doctrine): Response
    {
        // Action formulaire creation item
        
        // Entity Manager
        $entityManager = $doctrine->getManager();

        $item = new Item();
        $item->setLabel($request->request->get('label'));
        $item->setPrice($request->request->get('price'));
        $item->setQuantity($request->request->get('quantity'));
        $item->setLocation($request->request->get('location'));
        $item->setCreatedAt(new \DateTime);
        $entityManager->persist($item);
        $entityManager->flush();

        $this->addFlash('success', 'Item created !');
        return $this->redirectToRoute('entity_items_create');
    }

    public function show(ManagerRegistry $doctrine, $id): Response
    {
        // Affichage d'un seul item
        $repository = $doctrine->getRepository(Item::class);
        $item = $repository->find($id);

        if(empty($item)) {
            throw $this->createNotFoundException();
        }

        return $this->render('exo_entity_3/items/show-edit.html.twig', [
            'item' => $item,
            'edit' => false,
        ]);
    }

    public function edit(ManagerRegistry $doctrine, $id): Response
    {
        // Affichage vue formulaire de mise à jour item
        $repository = $doctrine->getRepository(Item::class);
        $item = $repository->find($id);

        if(empty($item)) {
            throw $this->createNotFoundException();
        }

        return $this->render('exo_entity_3/items/show-edit.html.twig', [
            'item' => $item,
            'edit' => true,
        ]);
    }

    public function update(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        // Action formulaire de mise à jour item
        $manager = $doctrine->getManager();    
        
        $repository = $doctrine->getRepository(Item::class);
        $item = $repository->find($id);

        if(empty($item)) {
            throw $this->createNotFoundException();
        }

        $item->setLabel($request->request->get('label'));
        $item->setPrice($request->request->get('price'));
        $item->setQuantity($request->request->get('quantity'));
        $item->setLocation($request->request->get('location'));

        $manager->flush();

        $this->addFlash('success', 'Item updated !');
        return $this->redirectToRoute('entity_items_show', [
            'id' => $item->getId(),
        ]);
    }

    public function delete(ManagerRegistry $doctrine, $id): Response
    {
        // Action destruction d'un item
        $manager = $doctrine->getManager();    
        
        $repository = $doctrine->getRepository(Item::class);
        $item = $repository->find($id);

        if(empty($item)) {
            throw $this->createNotFoundException();
        }

        $manager->remove($item);
        $manager->flush();

        $this->addFlash('success', 'Item deleted !');
        return $this->redirectToRoute('entity_items_index');
    }
}
