<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Make;
use App\Form\MakeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MakeController extends AbstractController
{

    /**
     * @Route("/{_locale<%app.supported_locales%>}/make", name="make")
     * */
    public function makeList(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Make::class);
        $makes = $repository->findAll();

        return $this->render('front/make/list.html.twig', [
            'makes' => $makes,
        ]);
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/make/{id<\d+>}", name="make_car_list")
     */
    public function eventSelected(Make $make, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Car::class);
        $cars = $repository->findByMakeId($make->getId());

        return $this->render('front/make/cars-for-make.html.twig', [
            'make' => $make,
            'cars' => $cars,
        ]);
    }
}