<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    protected $categoryRepository;
    
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/category', name: 'app_category')]
    public function renderMenuList() {
        // 1. aller chercher les categorie dans la BDD (repository)
        $categories = $this->categoryRepository->findAll();

        // 2. renvoyer le rendu html sous forme de Response ($this->render)
        return $this->render('category/_menu.html.twig', [
            'categories' => $categories
        ]);
    }
}
