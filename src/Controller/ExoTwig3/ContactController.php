<?php
namespace App\Controller\ExoTwig3;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('exo_twig3/contact/index.html.twig');
    }

    /**
     * @return Response
     */
    public function formProcess(Request $request): Response
    {
        dd(
            $request->query->all(),
            $request->request->all()
        );
    }
}
