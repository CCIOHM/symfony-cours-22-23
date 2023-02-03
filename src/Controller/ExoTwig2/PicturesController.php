<?php
namespace App\Controller\ExoTwig2;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PicturesController extends AbstractController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('/exo_twig2/pictures/index.html.twig');
    }
}
