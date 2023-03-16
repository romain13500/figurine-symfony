<?php

                                    // **** GESTION DU PANIER ****

namespace App\Cart;

use App\Repository\FigurinesRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class CartService
{
    protected $requestStack;
    protected $figurinesRepository;

    public function __construct(RequestStack $requestStack, FigurinesRepository $figurinesRepository)
    {
        $this->requestStack = $requestStack;
        $this->figurinesRepository = $figurinesRepository;
    }


    protected function getCart()
    {
        return $this->requestStack->getSession()->get('cart', []);
    }

    protected function saveCart(array $cart)
    {
        return $this->requestStack->getSession()->set('cart', $cart);
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


    public function remove(int $id) {
        $cart = $this->getCart();
        unset($cart[$id]);

        $this->saveCart($cart);
    }


    public function decrements(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }
        $cart[$id]--;

        $this->saveCart($cart);

    }


    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->requestStack->getSession()->get('cart', []) as $id => $qty) {
            $figurine = $this->figurinesRepository->find($id);

            if (!$figurine) {
                continue;
            }
            $total += $figurine->getPrice() * $qty / 100;
        }

        return $total;
    }



    public function detailedCartItems(): array
    {
        $detailedCart = [];

        foreach ($this->requestStack->getSession()->get('cart', []) as $id => $qty) {
            $figurine = $this->figurinesRepository->find($id);

            if (!$figurine) {
                continue;
            }

            $detailedCart[] = new CartItem($figurine, $qty);
        }

        return $detailedCart;
    }
}
