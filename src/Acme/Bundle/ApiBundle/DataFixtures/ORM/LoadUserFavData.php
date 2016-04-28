<?php
namespace Acme\Bundle\ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\UserManager;
use Acme\Bundle\ApiBundle\Entity\Fav;

class LoadUserFavData extends AbstractFixture 
                      implements OrderedFixtureInterface, ContainerAwareInterface
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

			$fav = new Fav();
			$fav->setMovieId(1);
			$fav->setStatus(1);
			$fav->setUserId($userApi->getId());
			$manager->persist($fav);
			$manager->flush();

			$fav = new Fav();
			$fav->setMovieId(1);
			$fav->setStatus(1);
			$fav->setUserId($userApi->getId());
			$manager->persist($fav);
			$manager->flush();

			$fav = new Fav();
			$fav->setMovieId(2);
			$fav->setStatus(3);
			$fav->setUserId($userApi->getId());
			$manager->persist($fav);
			$manager->flush();

			$fav = new Fav();
			$fav->setMovieId(2);
			$fav->setStatus(3);
			$fav->setUserId(10);
			$manager->persist($fav);
			$manager->flush();

			$fav = new Fav();
			$fav->setMovieId(2);
			$fav->setStatus(3);
			$fav->setUserId(11);
			$manager->persist($fav);
			$manager->flush();

	}

  /**
   * {@inheritDoc}
   */
  public function getOrder()
  {
      return 6;
  }

}