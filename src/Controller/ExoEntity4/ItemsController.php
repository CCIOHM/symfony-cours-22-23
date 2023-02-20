<?php
namespace App\Controller\ExoEntity4;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemsController extends AbstractController
{
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
