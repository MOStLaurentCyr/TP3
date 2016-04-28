<?php
namespace Cimmi\AuthBundle\DataFixtures\ORM;

use Cimmi\AuthBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData 
    extends AbstractFixture 
    implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        /** @var $user User */

        // AS : Administrateur système
        $user = $userManager->createUser();
        $user->setEmail('admin@admin.com');
        $user->setPlainPassword('admin');
        $user->setFirstName('Administrateur');
        $user->setLastName('Administrateur');
        $user->setEnabled(true);
        $user->addRole('ROLE_AS');
        $this->addReference('as-user', $user);
        $userManager->updateUser($user);

        // ETU : Étudiant
        $user = $userManager->createUser();
        $user->setEmail('etu@etu.com');
        $user->setFirstName('EtuFirstName');
        $user->setLastName('EtuLastName');
        $user->setPlainPassword('etu');
        $user->setEnabled(true);
        $user->addRole('ROLE_ETU');
        $this->addReference('etu-user', $user);
        $userManager->updateUser($user);

        // API User
        $user = $userManager->createUser();
        $user->setEmail('api@api.com');
        $user->setFirstName('EtuAPI');
        $user->setLastName('EtuApiLastName');
        $user->setPlainPassword('api');
        $user->setEnabled(true);
        $user->addRole('ROLE_API');
        $this->addReference('etu-api', $user);
        $userManager->updateUser($user);
    }

    /**
     * Sets the container.
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get the order of this fixture
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}