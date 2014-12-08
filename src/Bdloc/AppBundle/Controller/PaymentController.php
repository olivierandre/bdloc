<?php

namespace Bdloc\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    /**
     * @Route("/compte/choix-abonnement")
     */
    public function takeSubscriptionPaymentAction(Request $request)
    {
    	$params = array();

        if(empty($_POST)) {
            $message = "Veuillez effectuer un choix d'abonnement";
            $params['error'] = $this->get('session')->getFlashBag()->add(
                'message',
                $message
            );

        }

        return $this->render("payment/choix_abonnement.html.twig", $params);
    }

    /**
     * @Route("/compte/payer-abonnement")
     */
    public function takeFinePaymentAction()
    {
        $params = array();

    
        return $this->render("payment/payment.html.twig", $params);
    }

}
