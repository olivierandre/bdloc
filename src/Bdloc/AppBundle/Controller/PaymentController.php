<?php

namespace Bdloc\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Bdloc\AppBundle\Form\CreditCardType;
use Bdloc\AppBundle\Entity\CreditCard as Credit;

use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Transaction;

class PaymentController extends Controller
{
    /**
     * @Route("/compte/choix-abonnement")
     */
    public function takeSubscriptionPaymentAction(Request $request)
    {
    	$params = array();

        $abonnement = $request->request->get('optionsRadios');

        $creditCard = new Credit();
        $creditCardForm = $this->createForm(new CreditCardType(), $creditCard);
        $creditCardForm->handleRequest($request);

        if($creditCardForm->isValid()) {
            $user = $this->getUser();
            $abonnement = $creditCardForm->get('abonnement')->getData();
            if($abonnement === "mensuel") {
                $montant = "12.00";
            } else {
                $montant = "120.00";
            }

            $userCard = $creditCard->getUserCard();
            $explode = explode(" ", $userCard);
            $regex = '/(mr|MR|mlle|madame|monsieur|MLLE|Mme|Mr)/';
            foreach($explode as $ex) {
                if(!preg_match($regex, $ex)) {
                    echo $ex;
                }
            }
            die();

            // ### CreditCard
            // A resource representing a credit card that can be
            // used to fund a payment.
            $card = new CreditCard();
            $card->setType("visa");
            $card->setNumber($creditCard->getCardNumber());
            $card->setExpire_month($creditCard->getMonthValidUntil());
            $card->setExpire_year($creditCard->getYearValidUntil());
            $card->setCvv2($creditCard->getCodecvv());
            $card->setFirst_name("Joe");
            $card->setLast_name("Shopper");

            // ### FundingInstrument
            // A resource representing a Payer's funding instrument.
            // Use a Payer ID (A unique identifier of the payer generated
            // and provided by the facilitator. This is required when
            // creating or using a tokenized funding instrument)
            // and the `CreditCardDetails`
            $fi = new FundingInstrument();
            $fi->setCredit_card($card);

            // ### Payer
            // A resource representing a Payer that funds a payment
            // Use the List of `FundingInstrument` and the Payment Method
            // as 'credit_card'
            $payer = new Payer();
            $payer->setPayment_method("credit_card");
            $payer->setFunding_instruments(array($fi));

            // ### Amount
            // Let's you specify a payment amount.
            $amount = new Amount();
            $amount->setCurrency("EUR");
            $amount->setTotal($montant);

            // ### Transaction
            // A transaction defines the contract of a
            // payment - what is the payment for and who
            // is fulfilling it. Transaction is created with
            // a `Payee` and `Amount` types
            $transaction = new Transaction();
            $transaction->setAmount($amount);
            $transaction->setDescription("This is the payment description.");

            // ### Payment
            // A Payment Resource; create one using
            // the above types and intent as 'sale'
            $payment = new Payment();
            $payment->setIntent("sale");
            $payment->setPayer($payer);
            $payment->setTransactions(array($transaction));

            // ### Create Payment
            // Create a payment by posting to the APIService
            // using a valid ApiContext
            // The return object contains the status;

            //see kmj/paypalbridgebundle
            $apiContext = $this->get('paypal')->getApiContext();
            
            try {
                //$result = $payment->create($apiContext);

            } catch (\Paypal\Exception\PPConnectionException $pce) {
                print_r( json_decode($pce->getData()) );
            }

            // Enregistrement de la carte chez Paypal
            // For Sample Purposes Only.
            $request = clone $card;

            try {
                //$card->create($apiContext);
            } catch (Exception $ex) {
                ResultPrinter::printError("Create Credit Card", "Credit Card", null, $request, $ex);
                exit(1);
            }

            $credit->setPaypalid($card->getId());
            $credit->setUser($user);

            /*$em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();*/
        }

        return $this->render("payment/choix_abonnement.html.twig", $params);
    }

    /**
     * @Route("/compte/card")
     */
    public function cardAction() {
        $params = array();
        $card = new Credit();

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
