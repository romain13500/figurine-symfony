<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\FigurinesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    

    
    #[Route('/cart/add/{id}', name: 'cart_add', requirements: ['id' => '\d+'])]
    public function add($id, FigurinesRepository $figurinesRepository, CartService $cartService): Response
    {
        $figurine = $figurinesRepository->find($id);
        if(!$figurine) {
            throw $this->createNotFoundException('La figurine n\'existe pas');
        }

        $cartService->add($id);


        $this->addFlash('success', 'La figurine a bien été ajoutée au panier');

        return $this->redirectToRoute('figurine_show', [
            'category_slug' => $figurine->getCategory()->getSlug(),
            'slug' => $figurine->getSlug()
        ]);
    }


    #[Route('/cart', name: 'cart_show')]
    public function show(CartService $cartService){

        $detailedCart = $cartService->detailedCartItems();

        $total = $cartService->getTotal();

        return $this->render('cart/show.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }
}
