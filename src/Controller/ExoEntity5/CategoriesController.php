<?php
namespace App\Controller\ExoEntity5;

use App\Entity\Category;
use App\Entity\Item;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends AbstractController
{
    public function attachItem(ManagerRegistry $doctrine, $id, $item_id)
    {
        // Entity Manager
        $entityManager = $doctrine->getManager();

        $category = $entityManager->getRepository(Category::class)->find($id);
        $item = $entityManager->getRepository(Item::class)->find($item_id);

        if (empty($category) or empty($item)) {
            $this->addFlash('error', 'The category or item is not found !');
            return $this->redirectToRoute('entity5_items_index');
        }

        // Possibilité 1: Depuis Category
        $category->addItem($item);
        $entityManager->persist($category);

        // OU Possibilité 2:  Depuis Item
//        $item->setCategory($category);
//        $entityManager->persist($item);

        $entityManager->flush();
    }

    public function detachItem(ManagerRegistry $doctrine, $id, $item_id)
    {
        // Entity Manager
        $entityManager = $doctrine->getManager();

        $category = $entityManager->getRepository(Category::class)->find($id);
        $item = $entityManager->getRepository(Item::class)->find($item_id);

        if (empty($category) or empty($item)) {
            $this->addFlash('error', 'The category or item is not found !');
            return $this->redirectToRoute('entity5_items_index');
        }

        // Possibilité 1: Depuis Category
        $category->removeItem($item);
        $entityManager->persist($category);

        // OU Possibilité 2:  Depuis Item
//        $item->setCategory(null);
//        $entityManager->persist($item);

        $entityManager->flush();
    }
}
