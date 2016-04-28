<?php
namespace Acme\Bundle\ApiBundle\Tests\Controller\Rest;
use Acme\Bundle\ApiBundle\Tests\WebTestCase;

/**
 * FavControllerTest
 * @author G. Simard <gsimard@cegep-ste-foy.qc.ca>
 */
class FavControllerTest extends WebTestCase
{
    public function setUp()
    {
        parent::setup();
        $fixtures = array(
            'Cimmi\AuthBundle\DataFixtures\ORM\LoadUserData',
            'Acme\Bundle\ApiBundle\DataFixtures\ORM\LoadUserFavData',
        );
        $this->loadFixtures($fixtures);
    }

    public function testGetFavsWithoutToken()
    {        
        $client = static::createClient();
        $client->request('GET', '/api/favs/me');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 401);
    }

    public function testGetFavsForMe()
    {
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $client->request('GET', '/api/favs/me');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $content);
        $this->assertCount(3, $content);

        $fav = $content[0];
        $this->assertArrayHasKey('movie_id', $fav);
        $this->assertArrayHasKey('user_id', $fav);
        $this->assertArrayHasKey('status', $fav);
    }

    public function testGetFav()
    {
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $client->request('GET', '/api/favs/1');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $fav = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $fav);
        $this->assertArrayHasKey('id', $fav);
        $this->assertArrayHasKey('status', $fav);
        $this->assertArrayHasKey('movie_id', $fav);
    }

    public function testGetFavWithInvalidRole()
    {
        $client = $this->createAuthenticatedClient('admin@admin.com', 'admin');
        $client->request('GET', '/api/favs/1');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 403);
    }

    public function testGetInvalidFav()
    {
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $client->request('GET', '/api/favs/1200');
        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 410);
    }

    public function testPostNewFav()
    {
        $data = array
        (
            "movie_id"  => "4",
            "status"  => "1",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $headers = array('CONTENT_TYPE' => 'application/json');
        $content = json_encode($data);
        $client->request('POST', '/api/favs', 
                         array(), array(), $headers, $content);

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 201);
        $fav = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $fav);
        $this->assertArrayHasKey('id', $fav);
        $this->assertArrayHasKey('movie_id', $fav);
        $this->assertArrayHasKey('user_id', $fav);
    }

    public function testPostNewFavWithOBDMApiKey()
    {
        $data = array
        (
            "movie_id"  => "tt222221111111087856",
            "status"  => "1",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $headers = array('CONTENT_TYPE' => 'application/json');
        $content = json_encode($data);
        $client->request('POST', '/api/favs', 
                         array(), array(), $headers, $content);

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 201);
        $fav = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $fav);
        $this->assertArrayHasKey('id', $fav);
        $this->assertArrayHasKey('movie_id', $fav);
        $this->assertArrayHasKey('user_id', $fav);

        $client->request('GET', '/api/favs/me');
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $this->assertContains('tt222221111111087856', $response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertCount(4, $content);
    }

    public function testPostNewFavWithoutMovieId()
    {
        $data = array
        (
            "status"  => "1",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $headers = array('CONTENT_TYPE' => 'application/json');
        $content = json_encode($data);
        $client->request('POST', '/api/favs', 
                         array(), array(), $headers, $content);

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 403);
        $this->assertContains('You must provide a valid movieId', $response->getContent());
    }

    public function testPostNewFavWithMovieIdAndStatus()
    {
        $data = array
        (
            "status" => 2,
            "movie_id"  => "4",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $headers = array('CONTENT_TYPE' => 'application/json');
        $content = json_encode($data);
        $client->request('POST', '/api/favs', 
                         array(), array(), $headers, $content);

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 201);
    }

    public function testPutFav()
    {
        $data = array
        (
            "status"  => "10",
            "movie_id"  => "4",
        );

        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $headers = array('CONTENT_TYPE' => 'application/json');
        $content = json_encode($data);
        $client->request('PUT', '/api/favs/1', 
                         array(), array(), $headers, $content);

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 200);

        // Look for change
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $client->request('GET', '/api/favs/1');
        $response = $client->getResponse();

        $fav = json_decode($response->getContent(), true);
        $this->assertEquals('10', $fav['status']);
    }

    public function testPutFavThatIDoNotOwn()
    {
        $data = array
        (
            "status"  => "11",
            "movie_id"  => "4",
        );

        $client = $this->createAuthenticatedClient('admin@admin.com', 'admin');
        $headers = array('CONTENT_TYPE' => 'application/json');
        $content = json_encode($data);
        $client->request('PUT', '/api/favs/1', 
                         array(), array(), $headers, $content);

        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 403);
    }

    public function testDeleteFav()
    {
        $client = $this->createAuthenticatedClient('api@api.com', 'api');
        $headers = array('CONTENT_TYPE' => 'application/json');
        $client->request('DELETE', '/api/favs/1');
        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 204);

        $client->request('DELETE', '/api/favs/1');
        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 410);
    }

    public function testDeleteFavThatIDoNotOwn()
    {
        $client = $this->createAuthenticatedClient('admin@admin.com', 'admin');
        $headers = array('CONTENT_TYPE' => 'application/json');
        $client->request('DELETE', '/api/favs/1');
        $response = $client->getResponse();
        $this->assertStatusCodeResponse($response, 403);
    }
}
