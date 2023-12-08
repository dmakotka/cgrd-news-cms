<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class AdminPageTest extends TestCase
{
    private $client;
    private $container = [];

    protected function setUp(): void
    {
        $mock = new MockHandler([
            new Response(200, [], 'Success'),
            new Response(200, [], 'Success'),
            new Response(200, [], 'Success'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $history = Middleware::history($this->container);
        $handlerStack->push($history);

        $this->client = new Client(['handler' => $handlerStack]);
    }

    public function testCreateNews()
    {
        // When
        $this->client->request('POST', 'admin.php', [
            'form_params' => [
                'action' => 'create',
                'title' => 'Test News Title',
                'description' => 'Test News Description'
            ]
        ]);

        // Get the response from the transaction container
        $response = $this->container[0]['response'];

        // Then
        $this->assertEquals(200, $response->getStatusCode());

        $transaction = $this->container[0];
        $body = (string)$transaction['request']->getBody();
        parse_str($body, $parsedBody);

        $this->assertEquals('create', $parsedBody['action']);
        $this->assertEquals('Test News Title', $parsedBody['title']);
        $this->assertEquals('Test News Description', $parsedBody['description']);
    }

    public function testUpdateNews()
    {
        // When
        $this->client->request('POST', 'admin.php', [
            'form_params' => [
                'action' => 'update',
                'id' => 1,
                'title' => 'Updated News Title',
                'description' => 'Updated News Description'
            ]
        ]);

        // Get the response from the transaction container
        $response = $this->container[0]['response'];

        // Then
        $this->assertEquals(200, $response->getStatusCode());

        $transaction = $this->container[0];
        $body = (string)$transaction['request']->getBody();
        parse_str($body, $parsedBody);

        $this->assertEquals('update', $parsedBody['action']);
        $this->assertEquals(1, $parsedBody['id']);
        $this->assertEquals('Updated News Title', $parsedBody['title']);
        $this->assertEquals('Updated News Description', $parsedBody['description']);
    }

    public function testDeleteNews()
    {
        // When
        $this->client->request('POST', 'admin.php', [
            'form_params' => [
                'action' => 'delete',
                'id' => 1
            ]
        ]);

        // Get the response from the transaction container
        $response = $this->container[0]['response'];

        // Then
        $this->assertEquals(200, $response->getStatusCode());

        $transaction = $this->container[0];
        $body = (string)$transaction['request']->getBody();
        parse_str($body, $parsedBody);

        $this->assertEquals('delete', $parsedBody['action']);
        $this->assertEquals(1, $parsedBody['id']);
    }
}
