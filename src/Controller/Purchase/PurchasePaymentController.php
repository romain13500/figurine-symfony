<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Repository\FigurinesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PurchasePaymentController extends AbstractController {
    
     /**
     * @var CartService
     */
    protected $cartService;
    protected $figurinesRepository;

    public function __construct(FigurinesRepository $figurinesRepository, CartService $cartService)
    {
        $this->figurinesRepository = $figurinesRepository;
        $this->cartService = $cartService;
    }
    
    #[Route('/purchase/pay/{id}', name:'purchase_payment_form')]
    public function showCardForm()
    {
        $form = $this->createForm(CartConfirmationType::class);

        $detailedCart = $this->cartService->detailedCartItems();

        $total = $this->cartService->getTotal();


        return $this->render('purchase/payment.html.twig', [
            'items' => $detailedCart,
            'total' => $total,
            'confirmationForm' => $form->createView()
        ]);
    }

    public function paypal()
    {}
}