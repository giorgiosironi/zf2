<?php

namespace ZendTest\OAuth\HTTP;

use Zend\OAuth\HTTP,
    Zend\OAuth;

class RequestTokenTest extends \PHPUnit_Framework_TestCase
{

    protected $stubConsumer = null;

    public function setup()
    {
        $this->stubConsumer = new Consumer32874;
        $this->stubConsumer2 = new Consumer32874b;
        $this->stubHttpUtility = new HTTPUtility32874;
        OAuth\OAuth::setHttpClient(new HTTPClient32874);
    }

    public function teardown()
    {
        OAuth\OAuth::clearHttpClient();
    }

    public function testConstructorSetsConsumerInstance()
    {
        $request = new HTTP\RequestToken($this->stubConsumer, null, $this->stubHttpUtility);
        $this->assertType('ZendTest\\OAuth\\HTTP\\Consumer32874', $request->getConsumer());
    }

    public function testConstructorSetsCustomServiceParameters()
    {
        $request = new HTTP\RequestToken($this->stubConsumer, array(1,2,3), $this->stubHttpUtility);
        $this->assertEquals(array(1,2,3), $request->getParameters());
    }

    public function testAssembleParametersCorrectlyAggregatesOauthParameters()
    {
        $request = new HTTP\RequestToken($this->stubConsumer, null, $this->stubHttpUtility);
        $expectedParams = array (
            'oauth_consumer_key' => '1234567890',
            'oauth_nonce' => 'e807f1fcf82d132f9bb018ca6738a19f',
            'oauth_timestamp' => '12345678901',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_version' => '1.0',
            'oauth_callback' => 'http://www.example.com/local',
            'oauth_signature' => '6fb42da0e32e07b61c9f0251fe627a9c'
        );
        $this->assertEquals($expectedParams, $request->assembleParams());
    }

    public function testAssembleParametersCorrectlyAggregatesOauthParametersIfCallbackUrlMissing()
    {
        $request = new HTTP\RequestToken($this->stubConsumer2, null, $this->stubHttpUtility);
        $expectedParams = array (
            'oauth_consumer_key' => '1234567890',
            'oauth_nonce' => 'e807f1fcf82d132f9bb018ca6738a19f',
            'oauth_timestamp' => '12345678901',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_version' => '1.0',
            'oauth_callback' => 'oob', // out-of-band when missing callback - 1.0a
            'oauth_signature' => '6fb42da0e32e07b61c9f0251fe627a9c'

        );
        $this->assertEquals($expectedParams, $request->assembleParams());
    }

    public function testAssembleParametersCorrectlyAggregatesCustomParameters()
    {
        $request = new HTTP\RequestToken($this->stubConsumer, array(
            'custom_param1'=>'foo',
            'custom_param2'=>'bar'
        ), $this->stubHttpUtility);
        $expectedParams = array (
            'oauth_consumer_key' => '1234567890',
            'oauth_nonce' => 'e807f1fcf82d132f9bb018ca6738a19f',
            'oauth_timestamp' => '12345678901',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_version' => '1.0',
            'oauth_callback' => 'http://www.example.com/local',
            'custom_param1' => 'foo',
            'custom_param2' => 'bar',
            'oauth_signature' => '6fb42da0e32e07b61c9f0251fe627a9c'
        );
        $this->assertEquals($expectedParams, $request->assembleParams());
    }

    public function testGetRequestSchemeHeaderClientSetsCorrectlyEncodedAuthorizationHeader()
    {
        $request = new HTTP\RequestToken($this->stubConsumer, null, $this->stubHttpUtility);
        $params = array (
            'oauth_consumer_key' => '1234567890',
            'oauth_nonce' => 'e807f1fcf82d132f9bb018ca6738a19f',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '12345678901',
            'oauth_version' => '1.0',
            'oauth_callback_url' => 'http://www.example.com/local',
            'oauth_signature' => '6fb42da0e32e07b61c9f0251fe627a9c~',
            'custom_param1' => 'foo',
            'custom_param2' => 'bar'
        );
        $client = $request->getRequestSchemeHeaderClient($params);
        $this->assertEquals(
        'OAuth realm="",oauth_consumer_key="1234567890",oauth_nonce="e807f1fcf82d132f9b'
        .'b018ca6738a19f",oauth_signature_method="HMAC-SHA1",oauth_timestamp="'
        .'12345678901",oauth_version="1.0",oauth_callback_url='
        .'"http%3A%2F%2Fwww.example.com%2Flocal",oauth_signature="6fb42da0e32e07b61c9f0251fe627a9c~"',
            $client->getHeader('Authorization')
        );
    }

    public function testGetRequestSchemePostBodyClientSetsCorrectlyEncodedRawData()
    {
        $request = new HTTP\RequestToken($this->stubConsumer, null, $this->stubHttpUtility);
        $params = array (
            'oauth_consumer_key' => '1234567890',
            'oauth_nonce' => 'e807f1fcf82d132f9bb018ca6738a19f',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '12345678901',
            'oauth_version' => '1.0',
            'oauth_callback_url' => 'http://www.example.com/local',
            'oauth_signature' => '6fb42da0e32e07b61c9f0251fe627a9c~',
            'custom_param1' => 'foo',
            'custom_param2' => 'bar'
        );
        $client = $request->getRequestSchemePostBodyClient($params);
        $this->assertEquals(
            'oauth_consumer_key=1234567890&oauth_nonce=e807f1fcf82d132f9bb018c'
            .'a6738a19f&oauth_signature_method=HMAC-SHA1&oauth_timestamp=12345'
            .'678901&oauth_version=1.0&oauth_callback_url=http%3A%2F%2Fwww.example.com%2Flocal'
            .'&oauth_signature=6fb42da0e32e07b61c9f0251fe627a9c~'
            .'&custom_param1=foo&custom_param2=bar',
            $client->getRawData()
        );
    }

    public function testGetRequestSchemeQueryStringClientSetsCorrectlyEncodedQueryString()
    {
        $request = new HTTP\RequestToken($this->stubConsumer, null, $this->stubHttpUtility);
        $params = array (
            'oauth_consumer_key' => '1234567890',
            'oauth_nonce' => 'e807f1fcf82d132f9bb018ca6738a19f',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '12345678901',
            'oauth_version' => '1.0',
            'oauth_callback_url' => 'http://www.example.com/local',
            'oauth_signature' => '6fb42da0e32e07b61c9f0251fe627a9c',
            'custom_param1' => 'foo',
            'custom_param2' => 'bar'
        );
        $client = $request->getRequestSchemeQueryStringClient($params, 'http://www.example.com');
        $this->assertEquals(
            'oauth_consumer_key=1234567890&oauth_nonce=e807f1fcf82d132f9bb018c'
            .'a6738a19f&oauth_signature_method=HMAC-SHA1&oauth_timestamp=12345'
            .'678901&oauth_version=1.0&oauth_callback_url=http%3A%2F%2Fwww.example.com%2Flocal'
            .'&oauth_signature=6fb42da0e32e07b61c9f0251fe627a9c'
            .'&custom_param1=foo&custom_param2=bar',
            $client->getUri()->getQuery()
        );
    }

}

class Consumer32874 extends OAuth\Consumer
{
    public function getConsumerKey(){return '1234567890';}
    public function getSignatureMethod(){return 'HMAC-SHA1';}
    public function getVersion(){return '1.0';}
    public function getRequestTokenUrl(){return 'http://www.example.com/request';}
    public function getCallbackUrl(){return 'http://www.example.com/local';}
}

class Consumer32874b extends OAuth\Consumer
{
    public function getConsumerKey(){return '1234567890';}
    public function getSignatureMethod(){return 'HMAC-SHA1';}
    public function getVersion(){return '1.0';}
    public function getRequestTokenUrl(){return 'http://www.example.com/request';}
    public function getCallbackUrl(){return null;}
}

class HTTPUtility32874 extends HTTP\Utility
{
    public function __construct(){}
    public function generateNonce(){return md5('1234567890');}
    public function generateTimestamp(){return '12345678901';}
    public function sign(array $params, $signatureMethod, $consumerSecret,
        $accessTokenSecret = null, $method = null, $url = null)
    {
        return md5('0987654321');
    }
}

class HTTPClient32874 extends \Zend\HTTP\Client
{
    public function getRawData(){return $this->raw_post_data;}
}
