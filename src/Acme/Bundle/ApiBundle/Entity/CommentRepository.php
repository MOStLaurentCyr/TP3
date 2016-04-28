<?php
namespace Acme\Bundle\ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CommentRepository
 * https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony2/recuperer-ses-entites-avec-doctrine2
 */
class CommentRepository extends EntityRepository
{
	public function findAllComments()
	{
	    return $this
				->createQueryBuilder('a')
				->getQuery()
				->getResult()
				;
	}

	public function myFindOne($id)
	{
	  $qb = $this->createQueryBuilder('a');

	  $qb
	    ->where('a.id = :id')
	    ->setParameter('id', $id)
	  ;

	  return $qb
	    ->getQuery()
	    ->getResult()
	  ;

	}

}
