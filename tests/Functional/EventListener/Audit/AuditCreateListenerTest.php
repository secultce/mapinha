<?php

declare(strict_types=1);

namespace App\Tests\Functional\EventListener\Audit;

use App\DataFixtures\Entity\UserFixtures;
use App\Document\SpaceTimeline;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\SpaceTestFixtures;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditCreateListenerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/spaces';

    private ?DocumentManager $documentManager = null;

    protected function setUp(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $this->documentManager = $container->get(DocumentManager::class);
    }

    public function testAuditCreate(): void
    {
        $requestBody = SpaceTestFixtures::partial();

        $client = static::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        /** @var SpaceTimeline $spaceTimeline */
        $spaceTimeline = $this->documentManager->getRepository(SpaceTimeline::class)
            ->findOneBy(['resourceId' => $requestBody['id'], 'userId' => UserFixtures::USER_ID_2]);

        self::assertEquals('The resource was created', $spaceTimeline->getTitle());
        self::assertNotNull($spaceTimeline, 'Space document not found');
        self::assertSame($spaceTimeline->getFrom(), []);

        $this->documentManager->remove($spaceTimeline);
        $this->documentManager->flush();
    }
}
