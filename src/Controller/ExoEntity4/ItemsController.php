<?php
namespace App\Controller\ExoEntity4;

use App\Entity\Item;
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

        return $this->render('exo_entity_4/items/index.html.twig', [
            'items' => $items,
        ]);
    }

    public function test(ManagerRegistry $doctrine)
    {
        // Id for the test
        $id = 3;

        // Item Repository
        /** @var ItemRepository $repository */
        $repository = $doctrine->getRepository(Item::class);

        // Find
        dd(
            $repository->findByDocQueryLang($id),
            $repository->findByNative($id),
        );

        // Delete
//        dd(
//            $repository->deleteByDocQueryLang($id),
//            $repository->deleteByNative($id),
//        );
    }
}
