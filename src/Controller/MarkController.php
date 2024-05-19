<?php

namespace App\Controller;



use App\Entity\Mark;
use App\Form\ImageType;
use App\Form\MarkType;
use App\Repository\MarkRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MarkController extends AbstractController
{
    #[Route('/mark/add/product/{id}', name: 'app_mark')]
    public function index($id, Request $request, EntityManagerInterface $manager, MarkRepository $markRepository,ProductRepository $productRepository): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute("app_product");
        }

        $user = $this->getUser();
        $product = $productRepository->find($id);

        $existingMark = $markRepository->findOneBy([
            'author' => $user,
            'product' => $product,
        ]);

        if ($existingMark){
            $mark = $existingMark;
        }else{
            $mark = new Mark();

        }
        $form = $this->createForm(MarkType::class, $mark);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($existingMark) {
                $existingMark->setNumber($form->get('number')->getData());
                $manager->persist($existingMark);
            } else {
                $mark->setProduct($product);
                $mark->setAuthor($user);
                $manager->persist($mark);
            }
            $manager->flush();


        }
        return $this->redirectToRoute('app_show', ['id' => $id]);
    }
}