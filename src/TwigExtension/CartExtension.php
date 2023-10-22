<?php

namespace App\TwigExtension;

use Twig\TwigFunction;
use App\Service\CartService;
use Twig\Extension\AbstractExtension;

class CartExtension extends AbstractExtension
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('displayCart', [$this, 'displayCart']),
            new TwigFunction('displayTotalPrice', [$this, 'displayTotalPrice'])
        ];
    }

    public function displayCart()
    {
        return $this->cartService->getCartItems();
    }

    public function displayTotalPrice()
    {
        return $this->cartService->getTotalPrice();
    }
}