<?php

namespace App\Controller;


use App\Entity\Car;
use App\Repository\CarRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @Route("/search", name="search")
     */
    public function search(CarRepository $carRepository, EventRepository $eventRepository, Request $request): Response
    {
        $searchQuery = $request->query->get('search');

        // Find results in car table
        $cars = $carRepository->carsBySearchData($searchQuery);

        if (empty($cars)) {
            $cars = null;
        }

        //Find results in event table
        $events = $eventRepository->eventsBySearchData($searchQuery);

        if (empty($events)){
            $events = null;
        }

        return $this->render('front/search/search.html.twig', [
            'cars' => $cars,
            'events' => $events,
            'search' => $searchQuery
        ]);
    }
}
