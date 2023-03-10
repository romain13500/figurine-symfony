<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
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
}
