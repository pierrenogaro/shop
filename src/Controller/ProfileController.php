<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\PaymentMethod;
use App\Entity\Product;
use App\Form\AddressType;
use App\Form\PaymentMethodType;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function Sodium\add;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/create/address', name: 'app_address_create')]
    public function createAddress(Request $request, EntityManagerInterface $manager): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $address->setOwner($this->getUser());
            $manager->persist($address);
            $manager->flush();
            return $this->redirectToRoute("app_profile");
        }

        return $this->render('profile/address-create.html.twig', [
            "form"=>$form->createView(),
            "btnValue"=>"Ajouter"

        ]);
    }

    #[Route('/profile/create/paymentmethod', name: 'app_create_paymentmethod', priority: 2)]
    public function createPaymentMethod(Request $request, EntityManagerInterface $manager): Response
    {
        $paymentMethod = new PaymentMethod();
        $form = $this->createForm(PaymentMethodType::class, $paymentMethod);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $paymentMethod->setOwner($this->getUser());
            $manager->persist($paymentMethod);
            $manager->flush();
            return $this->redirectToRoute("app_profile");
        }

        return $this->render('profile/payment-create.html.twig', [
            "form"=>$form->createView(),
            "btnValue"=>"Ajouter"

        ]);
    }

    #[Route('/profile/delete/address/{id}', name: 'app_delete_address', priority: 2)]
    public function deleteAddress(EntityManagerInterface $manager, Address $address): Response
    {
        $manager->remove($address);
        $manager->flush();
        return $this->redirectToRoute("app_profile");
    }

    #[Route('/profile/delete/paymentmethod/{id}', name: 'app_delete_paymentmethod', priority: 2)]
    public function deletePaymentMethod(EntityManagerInterface $manager, PaymentMethod $paymentMethod): Response
    {
        $manager->remove($paymentMethod);
        $manager->flush();
        return $this->redirectToRoute("app_profile");
    }

    #[Route('/profile/edit/address/{id}', name: 'app_edit_address', priority: 4)]
    public function editAddress(Request $request, EntityManagerInterface $manager, Address $address):Response
    {
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address->setOwner($this->getUser());
            $manager->persist($address);
            $manager->flush();
            return $this->redirectToRoute("app_profile");
        }

        return $this->render('profile/address-create.html.twig', [
            "form"=>$form->createView(),
            "btnValue"=>"Editer"
        ]);

    }

    #[Route('/profile/edit/paymentmethod/{id}', name: 'app_edit_paymentmethod', priority: 4)]
    public function editPaymentMethod(Request $request, EntityManagerInterface $manager, PaymentMethod $paymentMethod):Response
    {
        $form = $this->createForm(PaymentMethodType::class, $paymentMethod);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $paymentMethod->setOwner($this->getUser());
            $manager->persist($paymentMethod);
            $manager->flush();
            return $this->redirectToRoute("app_profile");
        }

        return $this->render('profile/payment-create.html.twig', [
            "form"=>$form->createView(),
            "btnValue"=>"Editer"
        ]);

    }


}
