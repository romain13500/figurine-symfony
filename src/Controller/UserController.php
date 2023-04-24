<?php

namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserController extends AbstractController
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }
}
