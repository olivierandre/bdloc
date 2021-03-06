<?php

namespace Bdloc\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends EntityRepository {

	public function getAllInformation($id) {

        /*SELECT * FROM book b
        LEFT JOIN serie s ON b.serie_id = s.id
        LEFT JOIN author a ON b.illustrator = a.id 
        LEFT JOIN author au ON b.scenarist = au.id 
        LEFT JOIN author aut ON b.colorist = aut.id
        WHERE b.id = 7*/

        $query = $this->createQueryBuilder('book')
            ->addSelect('illustrator')
            ->addSelect('scenarist')
            ->addSelect('colorist')
            ->addSelect('serie')
            ->leftjoin('book.serie', 'serie')
            ->leftjoin('book.illustrator', 'illustrator')
            ->leftjoin('book.scenarist', 'scenarist')
            ->leftjoin('book.colorist', 'colorist')
            ->where('book.id = :id')
            ->setParameter(':id', $id)
            ->getQuery();

        return $query->getSingleResult();
    }

    public function getLooking($title, $author) {
        $query = $this->createQueryBuilder('book')
            ->addSelect('illustrator')
            ->leftjoin('book.illustrator', 'illustrator')
            ->where('book.title LIKE :title')
            ->andWhere('illustrator.lastName LIKE :author')
            ->setParameter('title', $title)
            ->setParameter('author', $author)
            ->getQuery();

        return $query->getResult();
    }

}
