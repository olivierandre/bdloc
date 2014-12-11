<?php

namespace Bdloc\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Bdloc\AppBundle\Form\CreditCardType;
use Bdloc\AppBundle\Entity\CreditCard as Credit;
use Bdloc\AppBundle\Entity\TransactionAbonnement;

use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Transaction;

// $card->setType("visa");
//        $card->setNumber("4417119669820331");
//        $card->setExpire_month("11");
//        $card->setExpire_year("2018");
//        $card->setCvv2("987");

class PaymentController extends Controller
{
    /**
     * @Route("/compte/abonnement")
     */
    public function takeSubscriptionPaymentAction(Request $request)
    {
    	$params = array();
        $params['idPayment'] = null;
        $doctrine = $this->getDoctrine();
        $user = $this->getUser();

        // On vérifie si l'utilisateur a déjà un abonnement
        $abonnement = $this->getUser()->getAbonnement();

        if(!$abonnement) {
            $creditCard = new Credit();
            $card = new CreditCard();
            $creditCardForm = $this->createForm(new CreditCardType(), $creditCard);
            $creditCardForm->handleRequest($request);

            if($creditCardForm->isValid()) {
                
                //see kmj/paypalbridgebundle
                $apiContext = $this->get('paypal')->getApiContext();

                $abonnement = $creditCardForm->get('abonnement')->getData();
                if($abonnement === "mensuel") {
                    $montant = "12.00";
                } else {
                    $montant = "120.00";
                }
                //  On vérifie si l'utilisateur a déjà une carte
                $cardUser = $doctrine->getRepository('BdlocAppBundle:CreditCard')->findOneByUser($user);
                $em = $doctrine->getManager();

                if(!empty($cardUser)) {
                    try {
                        $card = CreditCard::get($cardUser->getPaypalid(), $apiContext);
                    } catch (Exception $ex) {
                        ResultPrinter::printError("Get Credit Card", "Credit Card", $cardUser->getPaypalid(), null, $ex);
                        exit(1);
                        //  si ça casse, on redirige
                    }
                } else {
                    // La carte existe pas, on fait le nécessaire + enregistrement BDD
                    // ### CreditCard
                    // A resource representing a credit card that can be
                    // used to fund a payment.
                    $card->setType("visa");
                    $card->setNumber($creditCard->getCardNumber());
                    $card->setExpire_month($creditCard->getMonthValidUntil());
                    $card->setExpire_year($creditCard->getYearValidUntil());
                    $card->setCvv2($creditCard->getCodecvv());
                    // Traitement du nom du détenteur de la carte
                    $userCard = $creditCard->getUserCard();
                    $explode = explode(" ", $userCard);
                    $regex = '/(mr|MR|mlle|madame|monsieur|MLLE|Mme|Mr|Mlle)/';
                    $chaine = '';

                    foreach($explode as $key => $ex) {
                        if(!preg_match($regex, $ex)) {
                            if($key == 1) {
                                $card->setFirst_name($ex);
                            }
                            else {
                                $chaine .= $ex.' ';
                            }
                        }
                    }
                    
                    $card->setLast_name(substr($chaine, 0, strlen($chaine) - 1));
                    $em->persist($user);
                }
                
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
                try {
                    $result = $payment->create($apiContext);
                    //  L'ID de paiement
                    $idPayment = $result->getId();
                    $transactId = $result->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId();
                } catch (\Paypal\Exception\PPConnectionException $pce) {
                    $error =  json_decode($pce->getData());
                    $params['errors'] = $error;
                    $params['errorPaypal'] = "Erreur lors de la transaction avec Paypal";
                    return $this->ErrorTransactionAction($params);
                    //  Si ça casse, on redirige.
                }
                /*dump($result);
                die();*/
                // Enregistrement de la carte chez Paypal
                // For Sample Purposes Only.
                $request = clone $card;

                try {
                    $card->create($apiContext);
                } catch (Exception $ex) {
                    ResultPrinter::printError("Create Credit Card", "Credit Card", null, $request, $ex);
                    $params['errorPaypal'] = "Erreur lors de l'enregistrement de votre carte chez Paypal";
                    return $this->render("Exception/error.html.twig", $params);
                    exit(1);
                    //  Si ça casse, on redirige
                }

                $transactionAbonnement = new TransactionAbonnement();
                $transactionAbonnement->setUser($user);
                $transactionAbonnement->setTransactionId($transactId);
                $transactionAbonnement->setPaymentResource($idPayment);
                $transactionAbonnement->setTypeAbonnement($abonnement);

                /* Si on arrive ici, le traitement PayPal est OK
                 Dernière hydratation avant BDD*/
                $creditCard->setPaypalid($card->getId());
                $creditCard->setUser($user);
                $creditCard->setCardNumber($card->getNumber());
                // Maj de l'abonnement du client
                $user->setAbonnement($abonnement);
                $user->setDateAbonnement(new \DateTime);

                $em->persist($transactionAbonnement);
                $em->persist($creditCard);
                $em->flush();

                $params['idPayment'] = $idPayment;
                $params['transactId'] = $transactId;
            }
        } else {

        }

        return $this->render("payment/choix_abonnement.html.twig", $params);
    }

    /**
     * @Route("/compte/erreur-transaction-paypal")
     */
    public function ErrorTransactionAction($params) {
        return $this->render("Exception/error.html.twig", $params);
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
     * @Route("/compte/ma-carte")
     */
    public function showCardAction() {
        $params = array();
        $cardUser = $this->getDoctrine()->getRepository('BdlocAppBundle:CreditCard')->findOneByUser($this->getUser());

        $creditCardForm = $this->createFormBuilder($cardUser)
            ->add('cardNumber', null, array(
                'label' => "Numéro de carte de crédit",
                'attr' => array(
                    'disabled' => 'disabled'
                    )
                ))
            ->add('monthValidUntil', null, array(
                'label' => "Mois d'expiration",
                'attr' => array(
                    'disabled' => 'disabled'
                    )
                ))
            ->add('yearValidUntil', null, array(
                'label' => "Année d'expiration",
                'attr' => array(
                    'disabled' => 'disabled'
                    )
                ))
            ->add('userCard', null, array(
                'label' => "Nom du détenteur de la carte",
                'attr' => array(
                    'disabled' => 'disabled'
                    )
                ))
            ->getForm();

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
