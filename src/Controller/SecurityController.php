<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\ParameterBagUtils;
use Symfony\Component\Security\Core\Exception\LogicException;
use Symfony\Component\Security\Core\Exception\LogoutException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    #[Route(path: '/login', name: 'security_login', priority:1)]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }




    #[Route(path: '/logout', name: 'security_logout', priority:1)]
    public function logout(bool $validateCsrfToken = true): ?Response
    {
        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $this->container->get('security.token_storage');

        if (!($token = $tokenStorage->getToken()) || !$token->getUser()) {
            throw new LogicException('Unable to logout as there is no logged-in user.');
        }

        $request = $this->container->get('request_stack')->getMainRequest();

        if (!$firewallConfig = $this->container->get('security.firewall.map')->getFirewallConfig($request)) {
            throw new LogicException('Unable to logout as the request is not behind a firewall.');
        }

        if ($validateCsrfToken) {
            if (!$this->container->has('security.csrf.token_manager') || !$logoutConfig = $firewallConfig->getLogout()) {
                throw new LogicException(sprintf('Unable to logout with CSRF token validation. Either make sure that CSRF protection is enabled and "logout" is configured on the "%s" firewall, or bypass CSRF token validation explicitly by passing false to the $validateCsrfToken argument of this method.', $firewallConfig->getName()));
            }
            $csrfToken = ParameterBagUtils::getRequestParameterValue($request, $logoutConfig['csrf_parameter']);
            if (!\is_string($csrfToken) || !$this->container->get('security.csrf.token_manager')->isTokenValid(new CsrfToken($logoutConfig['csrf_token_id'], $csrfToken))) {
                throw new LogoutException('Invalid CSRF token.');
            }
        }

        $logoutEvent = new LogoutEvent($request, $token);
        $this->container->get('security.firewall.event_dispatcher_locator')->get($firewallConfig->getName())->dispatch($logoutEvent);

        $tokenStorage->setToken(null);

        return $logoutEvent->getResponse();
    }
}
