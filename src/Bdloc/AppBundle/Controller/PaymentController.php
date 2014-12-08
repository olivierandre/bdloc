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

        $abonnement = $request->request->get('optionsRadios');

        if(empty($abonnement)) {
            $message = "Veuillez choisir un abonnement";
            $params['error'] = $this->get('session')->getFlashBag()->add(
                'message',
                $message
            );
        }

        return $this->render("payment/choix_abonnement.html.twig", $params);
    }

    /**
     * @Route("/compte/amende")
     */
    public function takeFinePaymentAction()
    {
        $params = array();

    
        return $this->render("payment/amende.html.twig", $params);
    }

}
