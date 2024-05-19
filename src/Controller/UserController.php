<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Mark;
use App\Entity\Product;
use App\Form\CommentType;
use App\Form\ImageType;
use App\Form\MarkType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/product', name: 'app_product', priority: 1)]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, Request $request): Response
    {

        $categoryId = $request->query->get('category');

        if ($categoryId) {
            $category = $categoryRepository->find($categoryId);
            $products = $productRepository->findBy(['category' => $category]);
        } else {
            $products = $productRepository->findAll();
        }

        $categories = $categoryRepository->findAll();

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
            'categories' => $categories
        ]);
    }

    #[Route('/product/show/{id}', name: 'app_show')]
    public function show(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $mark = new Mark();
        $formMark = $this->createForm(MarkType::class, $mark);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);

        return $this->render('product/show.html.twig', [
            "product"=>$product,
            "form"=>$form->createView(),
            "formMark"=>$formMark->createView()
        ]);
    }

}
