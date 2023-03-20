<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Twig\Environment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PurchasesListController extends AbstractController {

    protected $security;
    protected $router;
    protected $twig;

    public function __construct(Security $security, RouterInterface $router, Environment $twig)
    {
        $this->security = $security;
        $this->router = $router;
        $this->twig = $twig;

        
    }
    
    #[Route('/purchases', name:'purchase_index', priority: 1)]
    public function index()
    {
                                // **** ASSURER LA CONNEXION ****
        
        /**
         * @var User
         */
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException("Vous devez etre connecté(e)");
        }

                                // **** SAVOIR QUI EST CONNECTÉ ****
        $html = $this->twig->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
        return new Response($html);
    } 
}