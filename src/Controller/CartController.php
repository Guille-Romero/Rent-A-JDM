<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Event;
use App\Repository\CarRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{_locale<%app.supported_locales%>}/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/add/{carId<\d+>}/{eventId<\d+>}", name="add")
     */
    public function addToCart($carId, $eventId, CarRepository $carRepository, EventRepository $eventRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Retrieve user cart
        $cart = $this->getUser()->getCart();

        $car = $carRepository->find($carId);
        $event = $eventRepository->find($eventId);

        if($request->isMethod('POST')){
            //Add selected car to cart
            $cart->addCar($car);
            //Add selected event to cart
            $cart->addEvent($event);

            // Persist the changes to the database
            $entityManager->persist($cart);
            $entityManager->flush();
            
            $this->addFlash('success', 'Items added to cart');
        }

        return $this->redirectToRoute('event_list');
    }

    /**
     * @Route("/show", name="show")
     */
    public function showCart()
    {
        // Retrieve user cart
        $cart = $this->getUser()->getCart();

        //Get events in user's cart
        $cartEvents = $cart->getEvent();

        //Get cars in user's cart
        $cartCars = $cart->getCar();

        //Total price
        $totalPrice = 0;
        foreach($cartEvents as $event){
            $totalPrice += $event->getSupplement(); 
        }

        foreach($cartCars as $car){
            $totalPrice += $car->getPrice(); 
        }



        return $this->render('cart/index.html.twig', [
            'cartCars' => $cartCars,
            'cartEvents' => $cartEvents,
            'total' => $totalPrice,
        ]);
    }

    /**
     * @Route("/remove-event/{id<\d+>}", name="remove_event")
     */
    public function removeEventFromCart(Event $event, EntityManagerInterface $entityManager)
    {
        //get user cart and remove the event selected
        $cart = $this->getUser()->getCart();
        $cart->removeEvent($event);

        // Persist the changes to the database
        $entityManager->persist($cart);
        $entityManager->flush();  

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/remove-car/{id<\d+>}", name="remove_car")
     */
    public function removeCarFromCart(Car $car, EntityManagerInterface $entityManager)
    {
        //get user cart and remove the car selected
        $cart = $this->getUser()->getCart();
        $cart->removeCar($car);

        // Persist the changes to the database
        $entityManager->persist($cart);
        $entityManager->flush();     

        return $this->redirectToRoute('cart_show');
    }
}
