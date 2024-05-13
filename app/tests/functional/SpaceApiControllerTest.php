<?php

declare(strict_types=1);

namespace App\Tests;

use App\DataFixtures\SpaceFixtures;
use App\Tests\fixtures\SpaceTestFixtures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SpaceApiControllerTest extends AbstractTestCase
{
    public function testGetSpacesShouldRetrieveAList(): void
    {
        $response = $this->client->request('GET', '/api/v2/spaces');
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($content);
    }

    public function testGetOneSpaceShouldRetrieveAObject(): void
    {
        $response = $this->client->request('GET', '/api/v2/spaces/1');
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsObject($content);
    }

    public function testCreateSpaceShouldReturnCreatedSpace(): void
    {
        $requestData = json_encode([
            'name' => 'Novo Espaço',
            'location' => [
                'latitude' => '45.4215',
                'longitude' => '-75.6981',
            ],
            'public' => true,
            'shortDescription' => 'Uma breve descrição do espaço.',
            'longDescription' => 'Descrição longa e detalhada do espaço.',
        ]);

        $response = $this->client->request('POST', '/api/v2/spaces', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => $requestData,
        ]);

        $content = json_decode($response->getContent(), true);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertIsArray($content);
        $this->assertEquals('Novo Espaço', $content['name']);
    }

    public function testDeleteSpaceShouldReturnSuccess(): void
    {
        $spaceId = 1;

        $response = $this->client->request('DELETE', '/api/v2/spaces/'.$spaceId);

        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->client->request('GET', '/api/v2/spaces/'.$spaceId);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $requestBody = SpaceTestFixtures::partial();
        $url = sprintf('/api/v2/spaces/%s', SpaceFixtures::SPACE_ID_3);

        $response = $this->client->request(Request::METHOD_PATCH, $url, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($requestBody),
        ]);

        $content = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertIsArray($content);
        foreach (array_keys($requestBody) as $key) {
            $this->assertEquals($requestBody[$key], $content[$key]);
        }
    }

    public function testUpdateNotFoundedResource(): void
    {
        $requestData = json_encode(SpaceTestFixtures::partial());
        $url = sprintf('/api/v2/spaces/%s', 1024);

        $response = $this->client->request(Request::METHOD_PATCH, $url, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => $requestData,
        ]);

        $error = [
            'error' => 'Space not found',
        ];

        $content = json_decode($response->getContent(false), true);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertIsArray($content);
        $this->assertEquals($error, $content);
    }
}
