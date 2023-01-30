<?php
namespace App\Controller\ExoControllers1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Test2Controller extends AbstractController
{
    /**
     * @return Response
     */
    public function index() : Response
    {
        // Créer une url depuis une route
        $url = $this->generateUrl(
            'test1_store', 
            ['id' => 11],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        dd($url); // Affiche uniquemennt pour observer l'url

        return new Response();
    }

    /**
     * @return Response|RedirectResponse
     */
    public function redirectTest1Index(): Response
    {
        // Redirection vers une route existante
        return $this->redirectToRoute('test1_index', [], 301);
    }

    /**
     * @return Response|RedirectResponse
     */
    public function redirectSymfony(): Response 
    {
        // Redirection vers une url extérieur ou qui n'a pas de route
        return $this->redirect('https://symfony.com', 301);
    }
}
