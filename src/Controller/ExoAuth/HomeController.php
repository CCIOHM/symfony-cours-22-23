<?php

namespace App\Controller\ExoAuth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('user_login');
        }

        return $this->render('exo_auth/home/index.html.twig');
    }
}
