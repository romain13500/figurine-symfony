<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class PurchaseConfirmationController {

    protected $formFactory;
    protected $router;
    protected $security;
    protected $cartService;

    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, Security $security, CartService $cartService)
    {
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->cartService = $cartService;   
    }
    
    #[Route("/purchase/confirm", name:"purchase_confirm")]
    public function confirm(Request $request, FlashBagInterface $flashBag)
    {
        dd('lol');
        $form = $this->formFactory->create(CartConfirmationType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {

            $flashBag->add('warning', 'Vous devez remplir le formulaire');
            return new RedirectResponse($this->router->generate('cart_show'));
        }

        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException("Vous devez être connecté(e) pour confirmer votre commande");
        }


        $cartItems = $this->cartService->detailedCartItems();

        if (count($cartItems) === 0) {
            $flashBag->add('warning', 'Votre panier est vide !!');
            return new RedirectResponse($this->router->generate('cart_show'));
        }
    } 
}