<?php

namespace App\Controller\Back;

use App\Entity\Cart;
use App\Entity\User;
use App\Form\UserType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/{_locale<%app.supported_locales%>}/back/user", name="back_user_")
 * */
class UserController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function userList(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(User::class);
        $users = $repository->findAll();

        return $this->render('back/user/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function createUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Build a form that allows the creation of a new user
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($user);
            $entityManager->flush();

            $entityManager->flush();

            $this->addFlash('success', 'User created successfully');


            return $this->redirectToRoute('back_user_list');
        }

        return $this->render('back/user/create.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id<\d+>}", name="update")
     */
    public function updateUser(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Build a form that allows the update of a car
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($user);
            $entityManager->flush();

            $entityManager->flush();

            $this->addFlash('success', 'User updated successfully');


            return $this->redirectToRoute('back_user_list');
        }

        return $this->render('back/user/create.html.twig', [
            'userForm' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/delete/{id<\d+>}", name="delete")
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager): Response
    {
        // Check if the user entity exists
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        $cart = $user->getCart();
        
        // Remove the user's cart from the database
        if ($cart) {
            $entityManager->remove($cart);
        }

        // Remove the user from the database
        $entityManager->remove($user);
        $entityManager->flush();
    
        $this->addFlash('success', 'User deleted successfully');
    
        return $this->redirectToRoute('back_user_list');
    }

    /**
     * @Route("/cart-for/{id<\d+>}", name="add_cart")
     */
    public function createCartForUser(User $user, EntityManagerInterface $entityManager): Response
    {
        if ($user->hasCart()){
            $this->addFlash('danger', 'User has already a shopping cart');
        }
        else{
            $repository = $entityManager->getRepository(User::class);

            // Replace 'username' with the actual identifier you use to find the user
            $userCart = $repository->findOneBy(['username' => $user->getUsername()]);

            if ($userCart) {
                // Create a new Cart entity
                $cart = new Cart();
                $cart->addUser($userCart);
                
                // Persist the cart
                $entityManager->persist($cart);
                $entityManager->flush();

                $this->addFlash('success', 'Cart added successfully');
            }
        }
        return $this->redirectToRoute('back_user_list');
    }
}
