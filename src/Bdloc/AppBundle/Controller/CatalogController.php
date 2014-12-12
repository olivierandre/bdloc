<?php

namespace Bdloc\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Bdloc\AppBundle\Form\BookType;
use Bdloc\AppBundle\Entity\Book;

class CatalogController extends Controller
{
    /**
     * @Route("/catalogue/{page}/{title}/{author}/{series}/{dispo}", 
     * defaults={"page" = 1, "title" = "all", "author" : "all", "series" : "all", "dispo":true}, 
     * requirements={"page": "\d+"})
     */
    public function showCatalogAction(Request $request, $page, $title, $author, $series, $dispo)
    {
    	$params = array();
        $serie = "";
        // Pagination
        $numberPerPage = 3;
        $limit = 1;

        //  Création du formulaire
        $book = new Book();
        $bookForm = $this->createForm(new BookType, $book);
        $bookForm->handleRequest($request);

        if($bookForm->isValid()) {
            // Récupération du titre indiqué
            $title = $bookForm->get('title')->getData();
            $author = $bookForm->get('illustrator')->getData();
            $title = $this->likeParameter($title);
            $author = $this->likeParameter($author);
            
            $series = $bookForm->get('serie')->getData();

        } else {
            $title = $this->likeParameter($title);
            $author = $this->likeParameter($author);
        }

        $bookRepository = $this->getDoctrine()->getRepository('BdlocAppBundle:Book');
        $books = $bookRepository->getLooking($title, $author);

        $params['books'] = $books;
        $params['bookForm'] = $bookForm->createView();

        return $this->render('catalog\show.html.twig', $params);
    }

    public function likeParameter($data) {
        if($data !== 'all') {
                $data = '%'.$data.'%';
            } else {
                $data = '%%';
            }
            return $data;
    }

}
