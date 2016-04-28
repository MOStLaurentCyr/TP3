<?php
namespace Acme\Bundle\ApiBundle\Tests\Controller\Rest;
use Acme\Bundle\ApiBundle\Tests\WebTestCase;

/**
 * CommentControllerTest
 * @author G. Simard <gsimard@cegep-ste-foy.qc.ca>
 */
class CommentControllerTest extends WebTestCase
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

    public function testGetCommentsWithoutToken()
    {        
        $client = static::createClient();
        $this->execQuery($client, 'GET', null, '/api/comments');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 401);
    }

    public function testGetComments()
    {
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->execQuery($client, 'GET', null, '/api/comments');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $content);
        $this->assertCount(3, $content);

        $comment = $content[0];
        $this->assertArrayHasKey('body', $comment);
        $this->assertArrayHasKey('status', $comment);
        $this->assertArrayNotHasKey('content', $comment);
        $this->assertArrayNotHasKey('password', $comment['user']);
    }

    public function testGetCommentsWithMovieIdParam()
    {
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->execQuery($client, 'GET', null, '/api/comments?movie_id=2');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $content);
        $this->assertCount(1, $content);

        $comment = $content[0];
        $this->assertContains('Mon commentaire 3', $response->getContent());
        $this->assertArrayHasKey('body', $comment);
        $this->assertArrayHasKey('status', $comment);
        $this->assertArrayNotHasKey('content', $comment);
        $this->assertArrayNotHasKey('password', $comment['user']);
    }

    public function testGetCommentsWithEmptyMovieIdParam()
    {
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->execQuery($client, 'GET', null, '/api/comments?movie_id=');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $content);
        $this->assertCount(3, $content);
    }

    public function testGetComment()
    {
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->execQuery($client, 'GET', null, '/api/comments/1');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $comment = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $comment);
        $this->assertArrayHasKey('body', $comment);
        $this->assertArrayHasKey('status', $comment);
        $this->assertArrayHasKey('user', $comment);
    }

    public function testGetCommentWithInvalidRole()
    {
        $client = $this->createAuthenticatedClient('admin@admin.com', 'admin');
        $this->execQuery($client, 'GET', null, '/api/comments/1');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 403);
    }

    public function testGetInvalidComment()
    {
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->execQuery($client, 'GET', null, '/api/comments/1200');
        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 410);
    }

    public function testPostNewComment()
    {
        $data = array
        (
            "body"  => "This is a comment",
            "status"  => "1",
            "user_id" => "1",
            "movie_id"  => "4",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->postData($client, $data, '/api/comments');
        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 201);
        $comment = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $comment);
        $this->assertArrayHasKey('id', $comment);
        $this->assertArrayHasKey('movie_id', $comment);
        $this->assertArrayHasKey('body', $comment);
        $this->assertArrayNotHasKey('password', $comment['user']);
    }

    public function testPostNewCommentWithOmdbId()
    {
        $data = array
        (
            "body"  => "This is a comment",
            "status"  => "1",
            "user_id" => "1",
            "movie_id"  => "stringId",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->postData($client, $data, '/api/comments');
        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 201);
        $comment = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $comment);
        $this->assertArrayHasKey('id', $comment);
        $this->assertArrayHasKey('movie_id', $comment);
        $this->assertArrayHasKey('body', $comment);

        $this->assertContains('stringId', $response->getContent());
    }

    public function testPostNewCommentWithoutBody()
    {
        $data = array
        (
            "status"  => "1",
            "user_id" => "1",
            "movie_id"  => "4",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->postData($client, $data, '/api/comments');

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 403);
        $this->assertContains('Cette valeur ne doit pas', $response->getContent());
    }

    public function testPostNewCommentWithBodyAndMovie()
    {
        $data = array
        (
            "body" => 'My new simple comment',
            "movie_id"  => "4",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->postData($client, $data, '/api/comments');

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 201);
    }

    public function testPostNewCommentWithoutMovieId()
    {
        $data = array
        (
            "body" => 'My new simple comment',
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->postData($client, $data, '/api/comments');

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 403);
    }

    public function testPutComment()
    {
        $data = array
        (
            "body"  => "modified body from put req",
            "status"  => "1",
            "user_id" => "1",
            "movie_id"  => "4",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->execQuery($client, 'PUT', $data, '/api/comments/1');

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 200);

        // Look for change
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->execQuery($client, 'GET', null, '/api/comments/1');
        $response = $client->getResponse();

        $comment = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('body', $comment);
        $this->assertContains('modified body', $comment['body']);
        $this->assertArrayNotHasKey('password', $comment['user']);
    }

    public function testPutCommentThatIDoNotOwn()
    {
        $data = array
        (
            "body"  => "HACK modified body from put req",
            "status"  => "1",
            "movie_id"  => "4",
        );

        $client = $this->createAuthenticatedClient('admin@admin.com', 'admin');
        $this->execQuery($client, 'PUT', $data, '/api/comments/1');

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 403);
    }

    public function testDeleteCommentThatIDoNotOwn()
    {
        $client = $this->createAuthenticatedClient('admin@admin.com', 'admin');
        $this->execQuery($client, 'DELETE', null, '/api/comments/1');
        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 403);
    }

    public function testDeleteComment()
    {
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $this->execQuery($client, 'DELETE', null, '/api/comments/1');
        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 204);

        $client->request('DELETE', '/api/comments/1');
        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 410); // GONE
    }

}
