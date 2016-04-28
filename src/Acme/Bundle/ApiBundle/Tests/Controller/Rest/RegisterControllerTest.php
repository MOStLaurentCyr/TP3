<?php
namespace Acme\Bundle\ApiBundle\Tests\Controller\Rest;
use Acme\Bundle\ApiBundle\Tests\WebTestCase;

/**
 * RegisterControllerTest
 * http://symfony.com/doc/current/book/testing.html
 * @author G. Simard <gsimard@cegep-ste-foy.qc.ca>
 */
class RegisterControllerTest extends WebTestCase
{
    public function setUp()
    {
        parent::setup();
        $fixtures = array(
            'Cimmi\AuthBundle\DataFixtures\ORM\LoadUserData',
            'Acme\Bundle\ApiBundle\DataFixtures\ORM\LoadUserCommentData',
        );
        $this->loadFixtures($fixtures);
    }

    public function testPostRegister()
    {
        $data = array
        (
            "email"=>"validemail@mynicedomain.com",
            "username"=>"username1",
            "firstname"=>"firstname1",
            "lastname"=>"lastname",
            "password"=>"lastname1",
        );
        $client = $this->createAnonymousClient();
        $this->postData($client, $data, '/api/register');

        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPostRegisterWithBadEmail()
    {
        $data = array
        (
            "username"  => "username1",
            "email"     => "invalidEmail",
            "firstname" => "firstname1",
            "password"  => "lastname1",
        );

        $client = $this->createAnonymousClient();
        $this->postData($client, $data, '/api/register');

        $response = $client->getResponse();
        $this->assertEquals(410, $response->getStatusCode());
    }

    public function testPostRegisterWithDuplicatedEmail()
    {
        $data = array
        (
            "email"=>"my@memail.com",
            "username"=>"username1",
            "firstname"=>"firstname1",
            "lastname"=>"lastname",
            "password"=>"lastname1",
        );

        $client = $this->createAnonymousClient();
        $this->postData($client, $data, '/api/register');

        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());

        $client = $this->createAnonymousClient();
        $this->postData($client, $data, '/api/register');

        $response = $client->getResponse();
        $this->assertEquals(410, $response->getStatusCode());
    }

    public function testPostRegisterWithGoodEmailAndNoPassword()
    {
        $data = array
        (
            "username"  => "username1",
            "email"     => "valid@email.com",
            "firstname" => "firstname1",
            "password"  => "",
        );

        $client = $this->createAnonymousClient();
        $this->postData($client, $data, '/api/register');

        $response = $client->getResponse();
        $this->assertEquals(410, $response->getStatusCode());
    }

}
