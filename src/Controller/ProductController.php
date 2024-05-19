<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Product;
use App\Form\CommentType;
use App\Form\ImageType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/admin/product', name: 'app_product_admin')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index_admin.html.twig', [
            'controller_name' => 'ProductController',
            "products"=>$productRepository->findAll(),
        ]);
    }

    #[Route('/admin/create/product', name: 'app_create_product', priority: 2)]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $product->setCreatedAt(new \DateTime());
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute("app_product_admin");
        }


        return $this->render('product/create.html.twig', [
            "product"=>$product,
            "form"=>$form->createView(),
            "btnValue"=>"Ajouter"
        ]);
    }

    #[Route('/admin/product/show/{id}', name: 'app_show_admin', methods: ['GET', 'POST'], priority: 2)]
    public function show(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $image = new Image();
        $formImage = $this->createForm(ImageType::class, $image);
        $formImage->handleRequest($request);
        if($formImage->isSubmitted() && $formImage->isValid())
        {
            $image->setProduct($product);
            $manager->persist($image);
            $manager->flush();
            return $this->redirectToRoute("app_show_admin", ["id"=>$product->getId()]);

        }



        return $this->render('product/show_admin.html.twig', [
            "product"=>$product,
            "formImage"=>$formImage->createView()
        ]);
    }


    #[Route('/admin/product/delete/{id}', name: 'app_delete_product', priority: 3)]
    public function delete(EntityManagerInterface $manager, Product $product):Response
    {
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute("app_product");
    }

    #[Route('/admin/product/edit/{id}', name: 'app_edit_product', priority: 4)]
    public function edit(Request $request, EntityManagerInterface $manager, Product $product):Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new \DateTime());
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute("app_show", ["id" => $product->getId()]);
        }

        return $this->render('product/create.html.twig', [
            "form"=>$form->createView(),
            "btnValue"=>"Editer"
        ]);

    }

    #[Route('/admin/product/images/{id}', name:"product_image", priority: 5)]
    public function addImage(Product $product):Response
    {
        $image = new Image();
        $formImage = $this->createForm(ImageType::class, $image);

        return $this->render("product/image.html.twig", [
            "product" => $product,
            'formImage' => $formImage->createView()
        ]);
    }

    #[Route('/admin/delete/image/{id}', name: 'delete_product_image', priority: 6)]
    public function deleteImage(EntityManagerInterface $manager, Image $image)
    {
        $product = $image->getProduct();
        $manager->remove($image);
        $manager->flush();
        return $this->redirectToRoute("app_show_admin", ["id"=>$product->getId()]
        );
    }


}
