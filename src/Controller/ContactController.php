<?php

namespace App\Controller;


use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
}
