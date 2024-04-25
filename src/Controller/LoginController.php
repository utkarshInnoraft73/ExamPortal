<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class LoginController.
 * Extends AbstractController.
 *
 * To mamange the client log and authentication of web app.
 */
class LoginController extends AbstractController
{

    /**
     * Public funtion login().
     *  To manage the login authenticain.
     *
     * @Route path(/login).
     *
     * @param AuthencationUtils $authenticationUtils
     *  To authenticate the utils.
     *
     * @return Response.
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Check if the user is avaible or not.
        if ($this->getUser()) {
            $userType = $this->getUser()->getUserType();

            // If user is admin role then render to app_admin route name.
            if($userType === 'admin') {
                return $this->redirectToRoute('app_admin');
            }

            // Else render the user dashboard.
            else {
                return $this->redirectToRoute('app_dashboard');
            }
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Public funtion logout().
     *  To manage the logout.
     *
     * @Route Path(/logout).
     *  Set the path to /logout
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
