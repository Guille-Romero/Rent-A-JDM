<?php

namespace App\Controller\Back;

use App\Entity\Make;
use App\Form\MakeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{_locale<%app.supported_locales%>}/back/make", name="back_make_")
 * */
class MakeController extends AbstractController
{

    /**
     * @Route("/list", name="list")
     */
    public function makeList(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Make::class);
        $makes = $repository->findAll();

        return $this->render('back/make/list.html.twig', [
            'makes' => $makes,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function createMake(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Build a form that allows the creation of a new make
        $make = new Make();
        $form = $this->createForm(MakeType::class, $make);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($make);
            $entityManager->flush();

            $entityManager->flush();

            $this->addFlash('success', 'Marque ajoutée');


            return $this->redirectToRoute('back_make_list');
        }

        return $this->render('back/make/create.html.twig', [
            'makeForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/read", name="read")
     */
    public function readMake(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Make::class);
        $makes = $repository->findAll();

        return $this->render('back/make/read.html.twig', [
            'makes' => $makes,
        ]);
    }

    /**
     * @Route("/update/{id<\d+>}", name="update")
     */
    public function updateMake(Make $make, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Build a form that allows the update of a car
        $form = $this->createForm(MakeType::class, $make);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($make);
            $entityManager->flush();

            $entityManager->flush();

            $this->addFlash('success', 'Marque ajoutée');


            return $this->redirectToRoute('back_make_list');
        }

        return $this->render('back/make/create.html.twig', [
            'makeForm' => $form->createView(),
            'make' => $make,
        ]);
    }

    /**
     * @Route("/delete/{id<\d+>}", name="delete")
     */
    public function deleteMake(Make $make, EntityManagerInterface $entityManager): Response
    {
        // Check if the make entity exists
        if (!$make) {
            throw $this->createNotFoundException('Make not found');
        }
    
        // Remove the make from the database
        $entityManager->remove($make);
        $entityManager->flush();
    
        $this->addFlash('success', 'Make deleted successfully');
    
        return $this->redirectToRoute('back_make_list');
    }
}