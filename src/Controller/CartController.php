<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart', priority: 2)]
    public function index(CartService $cartService): Response
    {

        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
            'total'=> $cartService->getTotal()
        ]);
    }
    #[Route('/admin/add/{id}/{quantity}', name: 'app_cart_add_admin', priority: 1)]
    #[Route('/add/{id}/{quantity}', name: 'app_cart_add', priority: 1)]
    #[Route('/addfromcart/{id}/{quantity}', name: 'app_cart_addfromcart')]
    public function addToCart(Request $request, Product $product, $quantity, CartService $cartService): Response
    {
        if(!$this->getUser()){return $this->redirectToRoute("app_login");}

        $cartService->addProduct($product, $quantity);

        $originRoute = $request->attributes->get('_route');
        $redirection = 'app_product';
        if($originRoute == "app_cart_addfromcart")
        {
            $redirection = 'app_cart';
        }
        if($originRoute == "app_cart_add_admin")
        {
            $redirection = 'app_product_admin';
        }

        return $this->redirectToRoute($redirection);

    }

    #[Route('/remove/{id}', name: 'app_cart_remove', priority: 3)]
    public function removeOneProduct(Product $product, CartService $cartService): Response
    {
        $cartService->removeOneProduct($product);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/removerow/{id}', name: 'app_cart_remove_row')]
    public function removeProductRow(Product $product, CartService $cartService): Response
    {
        $cartService->removeProductRow($product);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/emptycart', name: 'app_empty_cart', priority: 4)]
    public function emptyCart(CartService $cartService){
        $cartService->emptyCart();

        return $this->redirectToRoute('app_cart');
    }
}
