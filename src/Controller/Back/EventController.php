<?php

namespace App\Controller\Back;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{_locale<%app.supported_locales%>}/back/event", name="back_event_")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Event::class);
        $events = $repository->findAll();

        return $this->render('back/event/list.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function creaevent(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Build a form that allows the creation of a new event
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($event);
            $entityManager->flush();

            $entityManager->flush();

            $this->addFlash('success', 'Voiture ajoutée');


            return $this->redirectToRoute('back_event_list');
        }

        return $this->render('back/event/create.html.twig', [
            'eventForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/read/{id<\d+>}", name="read")
     */
    public function readEvent(Event $event): Response
    {
        return $this->render('back/event/read.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/update/{id<\d+>}", name="update")
     */
    public function updateEvent(Event $event, Request $request,EntityManagerInterface $entityManager): Response
    {
        // Build a form that allows the update of a event
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($event);
            $entityManager->flush();

            $entityManager->flush();

            $this->addFlash('success', 'Voiture editée');


            return $this->redirectToRoute('back_event_list');
        }

        return $this->render('back/event/create.html.twig', [
            'eventForm' => $form->createView(),
            'event' => $event,
        ]);
    }

    /**
     * @Route("/delete/{id<\d+>}", name="delete")
     */
    public function deleteEvent(Event $event, EntityManagerInterface $entityManager): Response
    {
        // Check if the event entity exists
        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }
    
        // Remove the event from the database
        $entityManager->remove($event);
        $entityManager->flush();
    
        $this->addFlash('success', 'Event deleted successfully');
    
        return $this->redirectToRoute('back_event_list');
    }
}
