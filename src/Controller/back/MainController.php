<?php

namespace App\Controller\back;


use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back", name="back_")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function homeBack(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Car::class);
        $cars = $repository->findAll();

        return $this->render('back/main/index.html.twig', [
            'cars' => $cars,
        ]);
    }
}
