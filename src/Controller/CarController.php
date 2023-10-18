<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Make;
use App\Form\CarType;
use App\Repository\MakeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarController extends AbstractController
{
    /**
     * @Route("/{_locale<%app.supported_locales%>}/car", name="cars")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Car::class);
        $cars = $repository->findAll();

        return $this->render('front/car/cars.html.twig', [
            'cars' => $cars,
        ]);
    }
}
