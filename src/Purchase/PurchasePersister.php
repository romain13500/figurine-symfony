<?php

namespace App\Purchase;

use App\Cart\CartService;
use DateTimeImmutable;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersister {

    protected $cartService;
    protected $em;
    protected $security;
    
    public function __construct(CartService $cartService, EntityManagerInterface $em, Security $security)
    {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->security = $security;
    }
    
    public function storePurchase(Purchase $purchase)
    {
                                    // 6. Lien avec l'utilisateur connecté (security)

        $purchase->setUser($this->security->getUser())
                ->setPurchasedAt(new DateTimeImmutable())
                ->setTotal($this->cartService->getTotal());

        $this->em->persist($purchase);

                                    // 7. Lien avec produit dans le panier (cartService)

        foreach ($this->cartService->detailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem->setPurchase($purchase)
                        ->setFigurine($cartItem->figurine)
                        ->setProductName($cartItem->figurine->getName())
                        ->setProductPrice($cartItem->figurine->getPrice())
                        ->setQuantity($cartItem->qty)
                        ->setTotal($cartItem->getTotal());



            $this->em->persist($purchaseItem);
        }


                                    // 8. Enregistrer la commande (EntityManagerInterface)

        $this->em->flush();
    }
}