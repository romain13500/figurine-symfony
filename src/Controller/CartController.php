<?php

namespace App\Controller;

use App\Repository\FigurinesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    

    
    #[Route('/cart/add/{id}', name: 'cart_add', requirements: ['id' => '\d+'])]
    public function add($id, FigurinesRepository $figurinesRepository, SessionInterface $session): Response
    {
        $figurine = $figurinesRepository->find($id);
        if(!$figurine) {
            throw $this->createNotFoundException('La figurine n\'existe pas');
        }

        $cart = $session->get('cart', []);

        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        /** @var FlashBag */
        $flashBag = $session->getBag('flashes');

        $flashBag->add('success', 'La figurine a bien été ajoutée au panier');
        
        return $this->redirectToRoute('figurine_show', [
            'category_slug' => $figurine->getCategory()->getSlug(),
            'slug' => $figurine->getSlug()
        ]);
    }


    #[Route('/cart', name: 'cart_show')]
    public function show(SessionInterface $session, FigurinesRepository $figurinesRepository){

        $total = 0;
        $detailedCart = [];

        foreach ($session->get('cart', []) as $id => $qty) {
            $figurine = $figurinesRepository->find($id);

            $detailedCart[] = [
                'figurine' => $figurinesRepository->find($id),
                'qty' => $qty
            ];
            $total += $figurine->getPrice() * $qty;
        }

        return $this->render('cart/show.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }
}
