<?php

namespace App\Controller;

use App\Repository\FigurinesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(FigurinesRepository $figurinesRepository)
    {
        $figurines = $figurinesRepository->findAll();
        dump($figurines);
        return $this->render('home/homepage.html.twig');
    }
}
