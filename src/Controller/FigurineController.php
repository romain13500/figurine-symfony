<?php

namespace App\Controller;

use App\Entity\Figurines;
use App\Form\FigurineType;
use App\Repository\CategoryRepository;
use App\Repository\FigurinesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigurineController extends AbstractController
{
    #[Route('/{slug}', name: 'figurine_category', priority: -1)]
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


    
    #[Route('/{category_slug}/{slug}', name:'figurine_show', priority:-1)] 
    public function show($slug, FigurinesRepository $figurinesRepository)
    {
        $figurine = $figurinesRepository->findOneBy(['slug' => $slug]);

        if (!$figurine) {
                throw new NotFoundHttpException("Le produit n'existe pas !!!");
        }

        return $this->render('figurine/show.html.twig', [
            'figurine' => $figurine,
        ]);
    }


    #[Route('/admin/figurine/create', name:'figurine_create')]
    public function create( FormFactoryInterface $factory, Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $figurine = new Figurines;

        $form = $this->createForm(FigurineType::class, $figurine);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $figurine->setSlug(strtolower($slugger->slug($figurine->getName())));

            $em->persist($figurine);
            $em->flush();

            return $this->redirectToRoute('figurine_show', [
                'category_slug' => $figurine->getCategory()->getSlug(),
                'slug' => $figurine->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('figurine/create.html.twig', [
            'formView' => $formView
        ]); 
    }
}
