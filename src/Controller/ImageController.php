<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ImageType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{
    #[Route('/admin/image/add/product/{id}', name: 'add_product_image')]
    public function index($id, Request $request, EntityManagerInterface $manager, ProductRepository $productRepository): Response
    {
        $image = new Image();
        $formImage = $this->createForm(ImageType::class, $image);
        $formImage->handleRequest($request);
        if($formImage->isSubmitted() && $formImage->isValid())
        {
            $image->setProduct($manager->getRepository(Product::class)->find($id));
            $manager->persist($image);
            $manager->flush();
        }
        return $this->redirectToRoute("product_image", ["id"=>$id]);
    }

    #[Route('admin//delete/image/{id}', name: 'delete_product_image')]
    public function delete(EntityManagerInterface $manager, Image $image)
    {
        $product = $image->getProduct();
        $manager->remove($image);
        $manager->flush();
        return $this->redirectToRoute("product_image", ["id"=>$product->getId()]
        );
    }
}
