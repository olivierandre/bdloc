<?php

namespace Bdloc\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Bdloc\AppBundle\Form\BookType;
use Bdloc\AppBundle\Entity\Book;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function homeAction()
    {
    	$params = array();
    	$book = new Book();

        $bookRepository = $this->getDoctrine()->getRepository('BdlocAppBundle:Book');
        $books = $bookRepository->findAll();

        $params['books'] = $books;

        return $this->render("default/home.html.twig", $params);
    }

}
