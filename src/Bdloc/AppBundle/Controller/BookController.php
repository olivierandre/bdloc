<?php

namespace Bdloc\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Bdloc\AppBundle\Form\BookType;
use Bdloc\AppBundle\Entity\Book;

class BookController extends Controller
{
    /**
     * @Route("/detail/{id}")
     */
    public function detailAction($id)
    {
    	$params = array();

        $bookRepository = $this->getDoctrine()->getRepository('BdlocAppBundle:Book');
        $book = new Book();
        $book = $bookRepository->getAllInformation($id);

        $cartController = $this->container->get('cart_controller');
        $bool = $cartController->isInCartAction($id);

        $params['book'] = $book;
        $params['bool'] = $bool;

        return $this->render("book/detail.html.twig", $params);
    }

    /**
     * @Route("/serie/{id}/{title}")
     */
    public function serieAction($id, $title)
    {
        $params = array();

        $bookRepository = $this->getDoctrine()->getRepository('BdlocAppBundle:Book');
        $books = new Book();
        $books = $bookRepository->findBySerie($id);

        $params['books'] = $books;
        $params['title'] = $title;

        return $this->render("book/serie_liste.html.twig", $params);
    }

}
