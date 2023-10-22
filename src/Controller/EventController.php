<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
* @Route("/{_locale<%app.supported_locales%>}/events", name="event_")
*/
class EventController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function mainEvent(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Event::class);
        $events = $repository->findAll();

        return $this->render('front/event/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="car_list")
     */
    public function eventSelected(Event $event, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Car::class);
        $cars = $repository->findByEventId($event->getId());

        return $this->render('front/event/cars-for-event.html.twig', [
            'event' => $event,
            'cars' => $cars,
        ]);
    }

    /**
     * @Route("/by-car/{id<\d+>}", name="by_car")
     */
    public function eventsByCar(Car $car, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Event::class);
        $events = $repository->findByCarId($car->getId());

        return $this->render('front/event/events-for-car.html.twig', [
            'events' => $events,
            'car' => $car
        ]);
    }
}
