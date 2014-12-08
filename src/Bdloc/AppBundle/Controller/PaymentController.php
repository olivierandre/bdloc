<?php

namespace Bdloc\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Bdloc\AppBundle\Form\CreditCardType;
use Bdloc\AppBundle\Entity\CreditCard;

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
     * @Route("/compte/card")
     */
    public function cardAction() {
        $params = array();
        $card = new CreditCard();

        $creditCardForm = $this->createForm(new CreditCardType(), $card);

        $params['creditCardForm'] = $creditCardForm->createView();
        
        return $this->render('BdlocAppBundle:Static:credit_card.html.twig', $params);
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
