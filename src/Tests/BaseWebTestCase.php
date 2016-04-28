<?php
namespace Tests;

use Cimmi\AuthBundle\Entity\User;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\DataCollectorTranslator;

abstract class BaseWebTestCase extends WebTestCase
{
    /** @var  \Symfony\Bundle\FrameworkBundle\Client */
    protected $client;

    /** @var  ReferenceRepository */
    protected $fixtures;

    /** @var DataCollectorTranslator */
    protected $translator;

    public function setUp()
    {
        parent::setUp();

        $this->client = $this->makeClient();
        $this->translator = $this->client->getContainer()->get('translator');
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager() {
        return $this->fixtures->getManager();
    }

    protected function authenticateUser($user)
    {
        $container = $this->client->getContainer();

        $session = $container->get('session');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $container->get('fos_user.security.login_manager')->logInUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $container->get('session')->set('_security_' . $firewallName, serialize($container->get('security.token_storage')->getToken()));
        $container->get('session')->save();
        $this->client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

    }

    /**
     * Make sure the access is denied for multiple routes and multiple users.
     * Please note, the wildcard * only works for user fixture having a reference ending by -user
     *
     * @param array $routes to test.
     * @param array $usersReferences to test. Accept wildcard * character.
     * @param array $ignoredUsersReferences User's references to ignore when the wildcard * is used.
     */
    public function verifyAccessDeniedForRoles(array $routes, array $usersReferences, array $ignoredUsersReferences = array())
    {
        if (in_array('*', $usersReferences)) {
            $usersReferences = array();
            foreach (array_keys($this->fixtures->getReferences()) as $reference) {
                if(preg_match('/-user$/i', $reference)) {
                    $usersReferences[] = $reference;
                }
            }
            $usersReferences = array_diff($usersReferences, $ignoredUsersReferences);
        }

        foreach ($routes as $route ){
            foreach ($usersReferences as $userReference) {
                $this->client = static::createClient();

                if (strlen($userReference) > 0) {
                    /** @var User $user */
                    $user = $this->fixtures->getReference($userReference);
                    $this->authenticateUser($user);
                }

                $this->client->request('GET', $route);

                $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode(), 'Route=' . $route . ', User_reference=' . $userReference);
            }
        }
    }

    /**
     * Make sure the access is granted for multiple routes and multiple users.
     * Please note, the wildcard * only works for user fixture having a reference ending by -user
     *
     * @param array $routes to test.
     * @param array $usersReferences to test. Accept wildcard * character.
     * @param array $ignoredUsersReferences User's references to ignore when the wildcard * is used.
     */
    public function verifyAccessGrantedForRoles(array $routes, array $usersReferences, array $ignoredUsersReferences = array()) {
        if (in_array('*', $usersReferences)) {
            $usersReferences = array();
            foreach (array_keys($this->fixtures->getReferences()) as $reference) {
                if(preg_match('/-user$/i', $reference)) {
                    $usersReferences[] = $reference;
                }
            }
            $usersReferences = array_diff($usersReferences, $ignoredUsersReferences);
        }

        foreach ($routes as $route ){
            foreach ($usersReferences as $userReference) {
                $this->client = static::createClient();

                if (strlen($userReference) > 0) {
                    /** @var User $user */
                    $user = $this->fixtures->getReference($userReference);
                    $this->authenticateUser($user);
                }

                $this->client->request('GET', $route);

                $this->assertTrue($this->client->getResponse()->getStatusCode() >= 200 && $this->client->getResponse()->getStatusCode() < 300, 'Route=' . $route . ', User_reference=' . $userReference);
            }
        }
    }

}