<?php

// src/Controller/PaymentController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PaymentMethodType;
use Stripe\Stripe;

class StripePaymentController extends AbstractController
{
    /**
     * @Route("/paiement", name="paiement")
     */
    public function paiement(Request $request)
    {
        Stripe::setApiKey($this->getParameter('stripe.secret_key'));

        $form = $this->createForm(PaymentMethodType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement du paiement avec Stripe
            // Ajoutez ici le code pour effectuer le paiement avec Stripe
            // Par exemple :
            /*
            $token = $_POST['stripeToken'];
            $charge = \Stripe\Charge::create([
                'amount' => 1000,
                'currency' => 'eur',
                'description' => 'Example charge',
                'source' => $token,
            ]);
            */

            $this->addFlash('success', 'Paiement effectué avec succès !');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('payment/paiement.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
