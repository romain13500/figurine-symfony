<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\FigurinesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{   

    /**
     * @var FigurinesRepository
     */
    protected $figurinesRepository;

    /**
     * @var CartService
     */
    protected $cartService;

    public function __construct(FigurinesRepository $figurinesRepository, CartService $cartService)
    {
        $this->figurinesRepository = $figurinesRepository;
        $this->cartService = $cartService;
    }



    #[Route('/cart/add/{id}', name: 'cart_add', requirements: ['id' => '\d+'])]
    public function add($id): Response
    {
        $figurine = $this->figurinesRepository->find($id);
        if(!$figurine) {
            throw $this->createNotFoundException('La figurine n\'existe pas');
        }

        $this->cartService->add($id);


        $this->addFlash('success', 'La figurine a bien été ajoutée au panier');

        return $this->redirectToRoute('figurine_show', [
            'category_slug' => $figurine->getCategory()->getSlug(),
            'slug' => $figurine->getSlug()
        ]);
    }


    #[Route('/cart', name: 'cart_show')]
    public function show(){

        $detailedCart = $this->cartService->detailedCartItems();

        $total = $this->cartService->getTotal();

        return $this->render('cart/show.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }


    #[Route('/cart/delete/{id}', name: 'cart_delete', requirements: ['id' => '\d+'])]
    public function delete($id){
        $figurine = $this->figurinesRepository->find($id);

        if (!$figurine) {
            throw $this->createNotFoundException("La figurine $id n\'existe pas !!");
        }
        $this->cartService->remove($id);

        $this->addFlash("success", "La figurine a était supprimée du panier !!");
        return $this->redirectToRoute("cart_show");

    }


    #[Route('/cart/decrements/{id}', name: 'cart_decrements', requirements: ['id' => '\d+'])]
    public function decrements($id){

        $figurine = $this->figurinesRepository->find($id);

        if (!$figurine) {
            throw $this->createNotFoundException("La figurine $id n'existe pas !");
        }

        $this->cartService->decrements($id);

        $this->addFlash("success", "La figurine a bien été supprimé !");
        return $this->redirectToRoute("cart_show");
    }
}
