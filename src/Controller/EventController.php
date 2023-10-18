<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
     * @Route("/{_locale<%app.supported_locales%>}/event", name="event")
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
     * @Route("/{_locale<%app.supported_locales%>}/event/{id<\d+>}", name="event_car_list")
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
}
