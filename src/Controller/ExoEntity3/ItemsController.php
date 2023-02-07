<?php
namespace App\Controller\ExoEntity3;

use App\Entity\Item;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemsController extends AbstractController
{
    public function index(): Response
    {
        // Affichage liste des items
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

    public function show($id): Response
    {
        // Affichage d'un seul item
    }

    public function edit($id): Response
    {
        // Affichage vue formulaire de mise à jour item
    }

    public function update(Request $request, $id): Response
    {
        // Action formulaire de mise à jour item
    }

    public function delete($id): Response
    {
        // Action destruction d'un item
    }
}
