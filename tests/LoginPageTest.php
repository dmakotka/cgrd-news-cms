<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class LoginPageTest extends TestCase
{
    private $client;
    private $container = [];

    protected function setUp(): void
    {
        $mock = new MockHandler([
            new Response(302, ['Location' => 'http://localhost/admin.php']),
            new Response(200, [], 'Invalid username or password'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $history = Middleware::history($this->container);
        $handlerStack->push($history);

        $this->client = new Client(['handler' => $handlerStack]);
    }

    public function testLoginRedirectToAdmin()
    {
        // Mock a successful login attempt that redirects to admin page
        $this->client->request('POST', 'login.php', [
            'form_params' => [
                'username' => 'testuser',
                'password' => 'correctpassword'
            ]
        ]);

        $transaction = $this->container[0];
        $response = $transaction['response'];

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('admin.php', $response->getHeaderLine('Location'));
    }

    public function testFailedLogin()
    {
        // Mock a failed login attempt
        $this->client->request('POST', 'login.php', [
            'form_params' => [
                'username' => 'wronguser',
                'password' => 'wrongpassword'
            ]
        ]);

        $transaction = $this->container[1];
        $response = $transaction['response'];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Invalid username or password', (string)$response->getBody());
    }
}
