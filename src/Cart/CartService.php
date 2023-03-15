<?php

                                    // **** GESTION DU PANIER ****

namespace App\Cart;

use App\Repository\FigurinesRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $requestStack;
    protected $figurinesRepository;

    public function __construct(RequestStack $requestStack, FigurinesRepository $figurinesRepository)
    {
        $this->requestStack = $requestStack;
        $this->figurinesRepository = $figurinesRepository;
    }

                                    // **** SI LA FIGURINE EXISTE DANS LE PANIER ALORS ON INCREMENTE LA QUANTITE
                                    // ****SINON ON AJOUTE LA FIGURINE AU PANIER

    public function add(int $id)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);

        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }


    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->requestStack->getSession()->get('cart', []) as $id => $qty) {
            $figurine = $this->figurinesRepository->find($id);
            $total += $figurine->getPrice() * $qty;
        }

        return $total;
    }

    public function detailedCartItems(): array
    {
        $detailedCart = [];

        foreach ($this->requestStack->getSession()->get('cart', []) as $id => $qty) {
            $figurine = $this->figurinesRepository->find($id);
            
            $detailedCart[] = [
                'figurine' => $figurine,
                'qty' => $qty
            ];
        }

        return $detailedCart;
    }
}
