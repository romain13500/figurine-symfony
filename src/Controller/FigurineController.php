<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\FigurinesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FigurineController extends AbstractController
{
    #[Route('/{slug}', name: 'figurine_category')]
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);
                // si la catégorie n'existe pas
        if (!$category) {
             throw $this->createNotFoundException('La catégorie n\'existe pas !!!');
        }

        return $this->render('figurine/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    
    #[Route('/{category_slug}/{slug}', name:'figurine_show')] 
    public function show($slug, FigurinesRepository $figurinesRepository)
    {
        $figurine = $figurinesRepository->findOneBy(['slug' => $slug]);

        if (!$figurine) {
                throw new NotFoundHttpException("Le produit n'existe pas !!!");
        }

        return $this->render('figurine/show.html.twig', [
            'figurine' => $figurine
        ]);
    }
}
