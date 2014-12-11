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
     * defaults={"page" = 1, "title" = "", "author" : "", "series" : "all", "dispo":true}, 
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
            $series = $bookForm->get('serie')->getData();
            $bookRepository = $this->getDoctrine()->getRepository('BdlocAppBundle:Book');
            $book = $bookRepository->findOneByTitle($title);

            $lenSeries = count($series);
            
            foreach($series as $key => $s) {

                if($key === $lenSeries - 1) {
                    $serie .= $s->getStyle();
                } else {
                    $serie .= $s->getStyle()."-";
                }
                
            }

            // return $this->redirect($this->generateUrl('bdloc_app_catalog_showcatalog', array(
            //     'page' => $page,
            //     'title' => $title,
            //     'author' => $author
            //     )
            // ));
        }
        
        // if(!empty($_GET['limit'])) {
        //     $limit = $_GET['limit'];
        // }

        $pagination = ($limit - 1) * $numberPerPage;
        // $totalQuestions = getAllQuestions();

        // $totalQuestions = ceil($totalQuestions/$numberPerPage);

        // $book = new Book();

        // $bookRepository = $this->getDoctrine()->getRepository('BdlocAppBundle:Book');
        // $books = $bookRepository->findAll();

        // $params['books'] = $books;


        //die();

        $params['bookForm'] = $bookForm->createView();

        return $this->render('catalog\show.html.twig', $params);
    }

}
