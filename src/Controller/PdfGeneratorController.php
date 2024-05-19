<?php

namespace App\Controller;

use App\Entity\Order;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PdfGeneratorController extends AbstractController
{
    #[Route('/pdf/generator/', name: 'app_pdf_generator')]
    public function index(Order $order): Response
    {
        $data = [
            "order"=>$order->getCustomer(),
            "products"=>$order->getOrderItems(),
            "deliveryAddress"=>$order->getDeliveryAddress(),
            "billingAddress"=>$order->getBillingAddress(),
            "total"=>$order->getTotal(),
        ];
        $html =  $this->renderView('pdf_generator/index.html.twig');


        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();


        return new Response (
            $dompdf->stream('commande.pdf', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );

    }
}
