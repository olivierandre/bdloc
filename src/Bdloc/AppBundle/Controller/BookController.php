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

        $params['book'] = $book[0];

        return $this->render("book/detail.html.twig", $params);
    }

}
