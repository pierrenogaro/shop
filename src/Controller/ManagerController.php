<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManagerController extends AbstractController
{
    #[Route('/manager/commandes', name: 'app_manager_commandes')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findAll();
        return $this->render('manager/index.html.twig', [
            'controller_name' => 'ManagerController',
            'orders' => $orders,
            'orderCount' => count($orders)
        ]);
    }

    #[Route('/manager/commandes/preparation', name: 'app_manager_commandes_preparation')]
    public function preparation(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(['deliveryStatus' => 0]);
        return $this->render('manager/preparation.html.twig', [
            'orders' => $orders,
            'orderCount' => count($orders)
        ]);
    }

    #[Route('/manager/commandes/termine', name: 'app_manager_commandes_termine')]
    public function termine(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(['deliveryStatus' => 2]);
        return $this->render('manager/termine.html.twig', [
            'orders' => $orders,
            'orderCount' => count($orders)
        ]);
    }

    #[Route('/manager/commandes/rupture', name: 'app_manager_commandes_rupture')]
    public function rupture(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(['deliveryStatus' => 1]);
        return $this->render('manager/rupture.html.twig', [
            'orders' => $orders,
            'orderCount' => count($orders)
        ]);
    }

    #[Route('/manager/commandes/{id}', name: 'app_manager_commandes_show')]
    public function show(Order $order): Response
    {
        return $this->render('manager/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/manager/commandes/status/{id}', name: 'app_manager_commandes_status')]
    public function status(Order $order, EntityManagerInterface $entityManager)
    {
        $deliveryStatus = $order->getDeliveryStatus();
        $newStatus = ($deliveryStatus + 1) % 3;  // This will cycle through 0, 1, 2
        $order->setDeliveryStatus($newStatus);

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('app_manager_commandes_show', ['id' => $order->getId()]);
    }

    #[Route('/manager/commandes/termine/{id}', name: 'app_manager_commandes_termine_status')]
    public function setTermine(Order $order, EntityManagerInterface $entityManager)
    {
        $order->setDeliveryStatus(2);
        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('app_manager_commandes_preparation');
    }

    #[Route('/manager/commandes/rupture/{id}', name: 'app_manager_commandes_rupture_status')]
    public function setRupture(Order $order, EntityManagerInterface $entityManager)
    {
        $order->setDeliveryStatus(1);
        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('app_manager_commandes_preparation');
    }

    #[Route('/manager/commandes/preparation/{id}', name: 'app_manager_commandes_preparation_status')]
    public function setPreparation(Order $order, EntityManagerInterface $entityManager)
    {
        $order->setDeliveryStatus(0);
        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('app_manager_commandes_termine');
    }

    #[Route('/manager/commandes/terminefromrupture/{id}', name: 'app_manager_commandes_termine_from_rupture')]
    public function setTermineFromRupture(Order $order, EntityManagerInterface $entityManager)
    {
        $order->setDeliveryStatus(2);
        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('app_manager_commandes_rupture');
    }
}

