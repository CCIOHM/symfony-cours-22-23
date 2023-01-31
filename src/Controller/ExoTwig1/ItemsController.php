<?php
namespace App\Controller\ExoTwig1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemsController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $extendValueTestableArray = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];

        return $this->render('/exo_twig1/items/index.html.twig', [
            'items' => $extendValueTestableArray,
            'display' => $request->get('display'), // Ou $request->query->get('display')
            'tableJson' => json_encode($extendValueTestableArray),
        ]);
    }
}
