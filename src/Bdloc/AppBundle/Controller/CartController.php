<?php

namespace Bdloc\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use Bdloc\AppBundle\Entity\Book;
use Bdloc\AppBundle\Entity\CartItem;
use Bdloc\AppBundle\Entity\Cart;

class CartController extends Controller
{    
    /**
     * @Route("/cart/ajouter/{idBook}")
     */
    public function addBookAction($idBook)
    {
    	$params = array();
        $cartItem = new CartItem();
        $cart = new Cart();
        $doctrine = $this->getDoctrine();      

        // On récupére l'objet user en session
        $user = $this->getUser();
        $book = $doctrine->getRepository('BdlocAppBundle:Book')->find($idBook);
        $findCart = $doctrine->getRepository('BdlocAppBundle:Cart')->findOneBy(
            array(
                'user' => $this->getUser(),
                'status' => 'En cours')
            );

        // Traitement pour CartItem
        $cartItem->setBook($book);
        
        //  Traitement pour Cart
        $cart->setUser($user);
        $cart->setStatus('En cours');
        $cart->addCartItem($cartItem);

        if($findCart) {
            $cart = $findCart;
            $cartItem->setCart($cart);
        } else {
            $cartItem->setCart($cart);
        }

        $em = $doctrine->getManager();
        $em->persist($cart);
        $em->persist($cartItem);
        $em->flush();

        // On redirige vers la page du livre
        $params['id'] = $idBook;
        return $this->redirect($this->generateUrl('bdloc_app_book_detail', $params));
    }

    /**
     * @Route("/cart/retirer/{idBook}")
     */
    public function removeBookAction($idBook) {
        $params = array();
        $cartItem = new CartItem();
        $cart = new Cart();
        $doctrine = $this->getDoctrine();

        // On récupére l'objet user en session
        $user = $this->getUser();

        $findCart = $doctrine->getRepository('BdlocAppBundle:Cart')->findOneBy(
            array(
                'user' => $this->getUser(),
                'status' => 'En cours')
            );

        $findItemCart = $doctrine->getRepository('BdlocAppBundle:CartItem')->findOneBy(
            array(
                'book' => $idBook,
                'cart' => $findCart)
            );

        $em = $doctrine->getManager();
        $em->remove($findItemCart);
        $em->flush();

        // On redirige vers la page du livre
        $params['id'] = $idBook;
        return $this->redirect($this->generateUrl('bdloc_app_book_detail', $params));
    }

    /**
     * @Route("/cart/")
     */
    public function recapCartAction() {
        $params = array();

        $params['books'] = $this->getCartUserAction();

        return $this->render("cart/cart.html.twig", $params);
    }

    public function getCartUserAction() {

        $cartRepository = $this->getDoctrine()->getRepository("BdlocAppBundle:Cart");
        $books = $cartRepository->getCartEnCours($this->getUser());
        
        return $books;
    }

    public function getSizeCartAction() {
        $params = array();
        $params['size'] = count($this->getCartUserAction());

        // Le template se trouve 'Bdloc/AppBundle/Resources/views/Cart'
        return $this->render('BdlocAppBundle:Cart:panier.html.twig', $params);
    }

    public function isInCartAction($idBook) {
        $bool = FALSE;
        $books = $this->getCartUserAction();

        foreach($books as $book) {
            if($book['id'] === (int)$idBook) {
                $bool = TRUE;
                break;
            }
        }
        return $bool;
    }


}
