<?php

namespace App\Controller\Entity2;

use App\Entity\ContactForm;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactFormsController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('exo_entity_2/contact_forms/index.html.twig');
    }

    public function formProcess(
        Request $request, 
        ManagerRegistry $doctrine
    ): Response {
        if ($request->request->get('legal') !== 'on') {
            $this->addFlash('error', 'Vous devez accepter les conditions.');
            return $this->redirectToRoute('entity_contact_index');
        }

        $contact = new ContactForm();
        $contact->setEmail($request->request->get('email'));
        $contact->setName($request->request->get('name'));
        $contact->setContent($request->request->get('content'));

        $manager = $doctrine->getManager();
        $manager->persist($contact);
        $manager->flush();

        $this->addFlash('success', 'Merci de nous avoir contacté !');
        return $this->redirectToRoute('entity_contact_index');
    }
}
