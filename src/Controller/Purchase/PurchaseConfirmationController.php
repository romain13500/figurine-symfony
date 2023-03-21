<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PurchaseConfirmationController extends AbstractController {

    
    protected $cartService;
    protected $em;
    protected $persister;
    
    public function __construct(CartService $cartService, EntityManagerInterface $em, PurchasePersister $persister)
    {      
        $this->cartService = $cartService;
        $this->em = $em;
        $this->persister = $persister;
    }

    #[Route('/purchase/confirm', name:'purchase_confirm')]
    #[IsGranted("ROLE_USER", message:"Vous devez être connecté !")]
    public function confirm(Request $request)
    {
                                    // 1. lire les données du formulaire

        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);

                                    // 2. si le formulaire n'est pas soumis : sortir

        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'Vous devez remplir le formulaire !');
            return $this->redirectToRoute('cart_show');
        }
                                    // 3. Si je ne suis pas connecté : sortir (security)

        $user = $this->getUser();

                                    // 4. Si aucun produit : sortir (cartService)

        $cartItem = $this->cartService->detailedCartItems();

        if (count($cartItem) === 0) {
                $this->addFlash('warning', 'Votre panier est vide');
                return $this->redirectToRoute('cart_show');
        }
        

                                    // 5. Creation purchase

         /**
         * @var Purchase
         */
        $purchase = $form->getData();

        $this->persister->storePurchase($purchase);
        $this->cartService->empty();
        $this->addFlash('success', 'Votre commande a bien été enregistrée !');
        return $this->redirectToRoute('purchase_index');

    }
}