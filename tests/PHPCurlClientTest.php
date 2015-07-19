<?php

class PHPCurlClientTest extends PHPUnit_Framework_TestCase
{
	
	public function testGetSuccess()
	{
		$client = new \PHPTools\PHPCurlClient\PHPCurlClient();

		$this->assertInstanceOf('PHPTools\PHPCurlClient\PHPCurlClient', $client);

		$result = $client->get('http://httpbin.org/get');

		$this->assertInstanceOf('PHPTools\PHPCurlClient\Response\PHPCurlClientResponse', $result);

		$this->assertObjectHasAttribute('request_headers',$result);

		$this->assertObjectHasAttribute('response_headers',$result);

		$this->assertObjectHasAttribute('body',$result);

		$this->assertObjectHasAttribute('error',$result);

		$this->assertEquals(0,$result->error->code);

		$this->assertNull($result->error->type);

		$this->assertNull($result->error->message);


		var_dump($result->request_headers);

	}
}