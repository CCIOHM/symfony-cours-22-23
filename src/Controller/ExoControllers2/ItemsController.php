<?php
namespace App\Controller\ExoControllers2;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemsController extends AbstractController
{
    /**
     * @return Response
     */
    public function show(Request $request): Response
    {
        // Exo phase 1 - Décode JSON (request)
        $body = $request->getContent();
        $jsonArray = json_decode($body, true);
        $jsonObject = json_decode($body);
        // dd($body, $jsonArray, $jsonObject);

        // Exo phase 2 - Encode JSON (response)
        $dataArray = [
            'defaultCurrency' => 'euro',
            'promotion' => false,

            'item' => [
                'id' => 10,
                'label' => 'Fake product', 
                'price' => 9.99,
                'currency' => 'euro',
            ],
            'pack' => [
                'id' => 954,
                'price' => 250,
                'currency' => 'euro',
                'productIds' => [ 10 ],
            ]
        ];

        return $this->json($dataArray);
    }
}
