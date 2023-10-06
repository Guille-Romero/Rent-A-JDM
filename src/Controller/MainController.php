<?php

namespace App\Controller;


use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function home(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Car::class);
        $cars = $repository->findAll();

        return $this->render('front/main/index.html.twig', [
            'cars' => $cars,
        ]);
    }
}
