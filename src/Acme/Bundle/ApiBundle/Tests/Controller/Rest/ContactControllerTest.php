<?php
namespace Acme\Bundle\ApiBundle\Tests\Controller\Rest;
use Acme\Bundle\ApiBundle\Tests\WebTestCase;

/**
 * ContactControllerTest
 * http://symfony.com/doc/current/book/testing.html
 * http://symfony.com/doc/current/cookbook/email/testing.html
 * @author G. Simard <gsimard@cegep-ste-foy.qc.ca>
 */
class ContactControllerTest extends WebTestCase
{
    public function testPostNewContact()
    {
        $data = array
        (
            "body"  => "This is a comment to thank you for...",
            "reason"  => "Support",
            "email" => "nice@email.com",
            "name"  => "My Name",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->postData($client, $data, '/api/contact');

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 201);
    }

    public function testPostNewContactWithoutBody()
    {
        $data = array
        (
            "reason"  => "Support",
            "email" => "nice@email.com",
            "name"  => "My Name"
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->postData($client, $data, '/api/contact');

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 403);
        $this->assertContains('You must provide a message body.', $response->getContent());
    }

    public function testPostNewContactWithoutEmail()
    {
        $data = array
        (
            "reason"  => "Support",
            "name"  => "My Name",
            "body"  => "Body",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->postData($client, $data, '/api/contact');

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 403);
        $this->assertContains('You must provide a valid email', $response->getContent());
    }

    public function testMailIsSentAndContentIsOk()
    {
        $client = static::createClient();

        // Enable the profiler for the next request
        $client->enableProfiler();

        $data = array
        (
            "body"  => "This is a comment to thank you for...",
            "reason"  => "Support",
            "email" => "nice@email.com",
            "name"  => "My Name",
        );

        $this->postData($client, $data, '/api/contact');
        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 201);

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // Check that an email was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());
        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('Contact :: Support', $message->getSubject());
        $this->assertEquals($data['body'], $message->getBody() );
    }
}
