<?php
namespace Acme\Bundle\ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\UserManager;
use Acme\Bundle\ApiBundle\Entity\Comment;

class LoadUserCommentData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
	    $this->container = $container;
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
	    $userApi = $this->getReference('etu-api');

			$comment = new Comment();
			$comment->setBody("Mon commentaire 1");
			$comment->setUser($userApi);
			$comment->setMovieId(1);
			$comment->setDateCreated(new \DateTime());
			$comment->setStatus(1);
			$manager->persist($comment);
			$manager->flush();

			$comment = new Comment();
			$comment->setBody("Mon commentaire 2");
			$comment->setUser($userApi);
			$comment->setMovieId(1);
			$comment->setDateCreated(new \DateTime());
			$comment->setStatus(1);
			$manager->persist($comment);
			$manager->flush();

			$comment = new Comment();
			$comment->setBody("Mon commentaire 3");
			$comment->setUser($userApi);
			$comment->setMovieId(2);
			$comment->setDateCreated(new \DateTime());
			$comment->setStatus(3);
			$manager->persist($comment);
			$manager->flush();
	}

  /**
   * {@inheritDoc}
   */
  public function getOrder()
  {
      return 5;
  }

}