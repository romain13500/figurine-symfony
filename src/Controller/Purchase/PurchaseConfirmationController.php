<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class PurchaseConfirmationController {

    protected $formFactory;
    protected $router;
    protected $security;
    protected $cartService;
    
    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, Security $security, CartService $cartService)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->security = $security;
        $this->cartService = $cartService;
    }
    

    
    #[Route("/purchase/confirm", name:"purchase_confirm")]
    public function confirm(Request $request, FlashBagInterface $flashBag)
    {
        dd('lol');
                                    // 1. lire les données du formulaire

        $form = $this->formFactory->create(CartConfirmationType::class);

        $form->handleRequest($request);

                                    // 2. si le formulaire n'est pas soumis : sortir

        if (!$form->isSubmitted()) {
            $flashBag->add('warning', 'Vous devez remplir le formulaire !');
            return new RedirectResponse($this->router->generate('cart_show'));
        }
                                    // 3. Si je ne suis pas connecté : sortir (security)

        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté(e)');
            
        }
                                    // 4. Si aucun produit : sortir (cartService)

        $cartItems = $this->cartService->detailedCartItems();

        if (count($cartItems) === 0) {
                $flashBag->add('warning', 'Votre panier est vide');
                return new RedirectResponse($this->router->generate('cart_show'));
        }
                                    // 5. Creation purchase
                                    // 6. Lien avec l'utilisateur connecté (security)
                                    // 7. Lien avec produit dans le panier (cartService)
                                    // 8. Enregistrer la commande (EntityManagerInterface)
    }
}