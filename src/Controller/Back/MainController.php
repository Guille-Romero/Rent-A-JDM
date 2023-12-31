<?php

namespace App\Controller\Back;


use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{_locale<%app.supported_locales%>}/back", name="back_")
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
