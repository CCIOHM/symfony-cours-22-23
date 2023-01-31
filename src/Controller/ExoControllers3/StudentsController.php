<?php

namespace App\Controller\ExoControllers3;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class StudentsController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('exo_controllers3/students/index.html.twig', [
            'controller_name' => 'StudentsController',
        ]);
    }

    public function edit(Request $request, $id): Response
    {
        dd(
            $request->headers->all(),
            $request->cookies->all()
        );

        return new Response();
    }

    /**
     * @param Request $request
     * @param KernelInterface $appKernel  Utiliser pour accèder au Kernel dont getProjectDir() => absolute path app
     * @param string|int $id
     * @return Response
     */
    public function update(Request $request, KernelInterface $appKernel, $id): Response
    {
        // Exemple avec http://.../students/update/5?testo=value2

        // Affichage des différentes données de l'url, $_GET, $_POST, Headers, Cookies, $_FILES
        dump(
            $appKernel->getProjectDir(), // Chemin absolue de l'app Symfony via le Kernel
            // (Kernel à inclure dans les paramètres de la méthode (sans oubliez le "use" de "KernelInterface" en haut) !)

            // Via URL
            $id, // 5 - Variable d'url
            $request->getRequestUri(), // /students/update/5?testo=value2 - Uri/Url relative
            $request->getUri(), // http://.../students/update/5?testo=value2  - Uri/Url Absolue
            $request->getMethod(), // POST - Methode HTTP

            // Via POST
            $request->request->get('key1'),
            $request->request->get('key2'),

            // Via GET (http://domain.com/studetns/update?var=value)
            $request->query->all(),

            // Via POST
            $request->request->all(),

            // Headers HTTP (dont Cookies en brute/raw)
            $request->headers->get('Content-Type'),
            $request->headers->all(),

            // Cookies HTTP (inclus dans le Headers "Cookies")
            $request->cookies->get('Content-Type'),
            $request->cookies->all(),

            // Via $_FILES ()
            $request->files->all()
        );

        /** @var UploadedFile $file */
        $file = $request->files->get('mon_fichier');
        if ($file) { // If fichier existe
            if ($file->isValid()) { // If fichier est valide (upload OK)
                dd(
                    $file->getSize(), // Taille en octet
                    $file->getMimeType(), // Mime Type du fichier

                    $file->getClientOriginalName(),
                    $file->getClientOriginalExtension(),

                    $file->getExtension(),
                    $file->guessExtension() // Extention la plus fiable
                );

                // Déplacment du fichier vers un dossier de Symfony (/storage/, ...) ou un espace de stockage du serveur
                // $file->move($appKernel->getProjectDir()."/storage/"+"FILE_NAME.EXTENSIONN"); // Le dossier /storage/ doit existé
            } else {
                // Erreur à l'utilisateur + Log/Erreur pour les devs
                dd("Fichier - Erreur d'upload");
            }
        } else {
            // Erreur de validation: Il manque le fichier
            dd('Fichier - Erreur de validation');
        }

        return new Response();
    }
}
