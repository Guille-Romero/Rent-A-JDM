<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/{_locale<%app.supported_locales%>}/login", name="login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $locale = $request->getLocale();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($request->isMethod('POST')){
            
            $request->setLocale($locale);

           dd($locale);
        }

        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/register", name="register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'Signed in successfully');

            return $this->redirectToRoute('login');
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/logout", name="logout")
     */
    public function logout(Request $request)
    {
        
        // Get the current locale
        $locale = $request->getLocale();
        dd($locale);
        // Create the target URL with the current locale
        $target = $this->generateUrl('main', ['_locale' => $locale]);
    
        return $this->redirect($target);
        
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/profile", name="profile")
     */
    public function profile()
    {
        return $this->render('user/profile.html.twig', [
        ]);
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/edit", name="edit")
     */
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if a new password is provided and hash it
            $newPassword = $form->get('plainPassword')->getData();
            if ($newPassword) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $newPassword));
            }
    
            // Save the edited user profile
            $entityManager->flush();
    
            $this->addFlash('success', 'Your profile has been successfully updated.');

            return $this->redirectToRoute('profile');
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
