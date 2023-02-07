<?php
namespace App\Controller\ExoTwig3;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController extends AbstractController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('exo_twig3/welcome/index.html.twig');
    }
}
