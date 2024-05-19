<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(private ProductRepository $productRepository, private RequestStack $requestStack)
    {

    }

    public function getCart()
    {
        $cart = $this->requestStack->getSession()->get("cart", []);
        $objectCart = [];
        foreach ($cart as $productId => $quantity) {
            $item = [
                "product" => $this->productRepository->find($productId),
                "quantity" => $quantity
            ];
            $objectCart[] = $item;
        }
        return $objectCart;
    }

    public function addProduct(Product $product, $quantity)
    {
        $cart = $this->requestStack->getSession()->get("cart", []);
        $productId = $product->getId();

        if(isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        }else  {
            $cart[$productId] = $quantity;
        }
        $this->requestStack->getSession()->set("cart",$cart);
    }


    public function removeOneProduct(Product $product)
    {
        $cart = $this->requestStack->getSession()->get("cart", []);
        $productId = $product->getId();

        if(isset($cart[$productId])) {
            $cart[$productId]--;

            if($cart[$productId] <= 0) {
                unset($cart[$productId]);
            }

            $this->requestStack->getSession()->set("cart", $cart);
        }
    }

    public function removeProductRow(Product $product)
    {
        $cart = $this->requestStack->getSession()->get("cart", []);
        $productId = $product->getId();

        if(isset($cart[$productId])) {
            unset($cart[$productId]);
        }
        $this->requestStack->getSession()->set("cart", $cart);

    }


    public function emptyCart(){
        $this->requestStack->getSession()->remove("cart");
    }

    public function getTotal()
    {
        $cart = $this->requestStack->getSession()->get("cart", []);
        $total = 0;
        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepository->find($productId);
            $total += $product->getPrice() * $quantity;
        }
        return $total;
    }

    public function getCount()
    {
        $cart = $this->requestStack->getSession()->get("cart", []);
        $count = 0;
        foreach ($cart as $quantity){
            $count += $quantity;
        }

        return $count;
    }
}
