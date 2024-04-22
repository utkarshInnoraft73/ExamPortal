<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class RegistrationController.
 * Extended AbstractContoller.
 *
 * To manage the controller related the user registration.
 */
class RegistrationController extends AbstractController
{

    /**
     * Route for user registration.
     * Route path(/resgister).
     * Route name (app_register).
     */
    #[Route('/register', name: 'app_register')]

    /**
     * Public function register().
     * To manage the rgisteration logic and the controller.
     *
     * @param Request @request.
     *  To submiting the user request.
     * @param UserPasswordHasherInterface $userPasswordHasher.
     *  To hash the user password.
     * @param EntityManagerInterface $entityManager.
     *  To manage the entities.
     *
     * @return Response
     *  Return the response.
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        // Create the instance of class User.
        $user = new User();

        // Create the form for class RegistrationFormType class.
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $roles = $form->get('roles')->getData();
            // foreach ($roles as $role) {
            //     if (!$authorizationChecker->isGranted($role)) {
            //         // Invalid role
            //         throw new \Exception('Invalid role selected');
            //     }
            // } // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('messages', 'Account created.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
