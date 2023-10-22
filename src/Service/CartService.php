<?php

namespace App\Service;

use App\Repository\CartRepository;
use Symfony\Component\Security\Core\Security;

class CartService
{
    private $security;
    private $cartRepository;

    public function __construct(Security $security, CartRepository $cartRepository)
    {
        $this->security = $security;
        $this->cartRepository = $cartRepository;
    }

    public function getCartItems()
    {
        // Retrieve the authenticated user
        $user = $this->security->getUser();

        if (!$user) {
            return [];
        }

        // Retrieve user cart
        $cart = $user->getCart();

        //Get events in user's cart
        $cartEvents = $cart->getEvent();

        //Get cars in user's cart
        $cartCars = $cart->getCar();

        $cartItems = [
            'cartEvents' => $cartEvents,
            'cartCars' => $cartCars
        ];

        // Fetch the cart items for the user
        return $cartItems;
    }

    public function getTotalPrice()
    {
        $cartItems = $this->getCartItems();

        $events = $cartItems['cartEvents'];
        $cars = $cartItems['cartCars'];

        $totalEvents = 0;

        //Get the price for each event in cart
        foreach ($events as $event){
            $totalEvents += $event->getSupplement();
        }

        $totalCars = 0;
        //Get the price for each event in cart
        foreach ($cars as $car){
            $totalCars += $car->getPrice();
        }

        return $totalEvents + $totalCars;
    }
}