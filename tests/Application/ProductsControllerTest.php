<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductsControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testSearchFull(): void
    {
        $this->client->request('GET', '/products');
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHasHeader('Content-Type');
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($response->getContent());
        $this->assertTrue(strpos($response->getContent(), "Ashlington leather ankle boots") !== false);
    }

    public function testSearchCategory(): void
    {
        $this->client->request('GET', '/products?category=boots');
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHasHeader('Content-Type');
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($response->getContent());
        $this->assertTrue(strpos($response->getContent(), "Ashlington leather ankle boots") !== false);
        $this->assertTrue(strpos($response->getContent(), "Nathane leather sneakers") === false);
    }

    public function testSearchByPrice(): void
    {
        $this->client->request('GET', '/products?priceLessThan=80.5');
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHasHeader('Content-Type');
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($response->getContent());
    }

    public function testSearchNoData(): void
    {
        $this->client->request('GET', '/products?category=botas');
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHasHeader('Content-Type');
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $data = $response->getContent();
        $this->assertJson($data);
        $this->assertEquals("[]", $data);
    }
}
