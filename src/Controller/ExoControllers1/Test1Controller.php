<?php
namespace App\Controller\ExoControllers1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Test1Controller extends AbstractController
{
    /**
     * @return Response
     */
    public function index() : Response
    {
        // Un dd() pour voir que l'on est bien arrivé ici
        dd('TEST 1 INDEX');

        return new Response(); 
    }

    /**
     * @return Response
     */
    public function store() : Response
    {
        return new Response();   
    }
}
