<?php

namespace Acme\Bundle\ApiBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase as LiipWebTestCase;
// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as LiipWebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * WebTestCase
 *
 * @author Jeremy Barthe <j.barthe@lexik.fr>
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 */
abstract class WebTestCase extends LiipWebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $authorizationHeaderPrefix = 'Bearer';

    /**
     * @var string
     */
    protected $queryParameterName = 'bearer';

    /**
     * @var string
     */
    protected $baseApiURL = 'appxapi.herokuapp.com';

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = 'user', $password = 'password')
    {
        $client = static::createClient();

        $client->request(
            'POST',
            $this->getUrl('login_check'),
            array(
                'username' => $username,
                'password' => $password,
            )
        );
        $response = $client->getResponse();
        $data     = json_decode($response->getContent(), true);

        return static::createClient(
            array(),
            array('HTTP_Authorization' => sprintf('%s %s', $this->authorizationHeaderPrefix, $data['token']),
                  'HTTP_HOST'    => 'appxapi.herokuapp.com',
                  'HTTP_USER_AGENT' => 'MySuperBrowser/1.0',
                )
        );
    }


    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAnonymousClient()
    {

        $client = static::createClient();
        return $client;
    }

    /**

     * @param Response $response
     * @param int      $statusCode
     * @param bool     $checkValidJson
     */
    protected function assertJsonResponse(Response $response, $statusCode = 200, $checkValidJson = true)
    {
        $this->assertEquals($statusCode, $response->getStatusCode(), $response->getContent());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);

        if ($checkValidJson) {
            $decode = json_decode($response->getContent(), true);
            $this->assertTrue(
                ($decode !== null && $decode !== false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }

    /**
     * @param Response $response
     * @param int      $statusCode
     * @param bool     $checkValidJson
     */
    protected function assertStatusCodeResponse(Response $response, $statusCode = 200)
    {
        $this->assertEquals($statusCode, $response->getStatusCode(), $response->getContent());
    }

    protected function postData($client, $data, $url)
    {
        $content = json_encode($data);
        $headers = array(
                     'CONTENT_TYPE' => 'application/json',
                   );
        $client->request('POST', $url, array(), array(), $headers, $content);
    }

    protected function execQuery($client, $method, $data, $url)
    {
        $content = null;
        if($data != null){
            $content = json_encode($data);    
        }
        
        $headers = array(
                 'CONTENT_TYPE' => 'application/json',
                );
        $client->request($method, $url, array(), array(), $headers, $content);
    }

}
