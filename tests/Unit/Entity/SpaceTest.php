<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\ActivityArea;
use App\Entity\Agent;
use App\Entity\ArchitecturalAccessibility;
use App\Entity\EntityAssociation;
use App\Entity\Space;
use App\Entity\SpaceAddress;
use App\Entity\SpaceType;
use App\Entity\Tag;
use App\Helper\DateFormatHelper;
use App\Tests\AbstractApiTestCase;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class SpaceTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromSpaceEntityShouldBeSuccessful(): void
    {
        $space = new Space();

        $spaceParent = new Space();
        $spaceParent->setId(Uuid::v4()::fromString('8e3e976d-0fc0-443e-bdd2-2b4d83da004f'));

        $spaceAddress = $this->createMock(SpaceAddress::class);

        $agent = new Agent();
        $agent->setId(Uuid::v4()::fromString('95f91eb5-cb62-4a7b-b677-8486d2a0763a'));

        $tag1 = new Tag();
        $tag1->setId(Uuid::v4());
        $tag1->setName('Cultura');
        $tag2 = new Tag();
        $tag2->setId(Uuid::v4());
        $tag2->setName('Test');

        $accessibility1 = new ArchitecturalAccessibility();
        $accessibility1->setId(Uuid::v4());
        $accessibility1->setName('Rampas');
        $accessibility1->setDescription('Rampas de acesso para cadeirantes');
        $accessibility2 = new ArchitecturalAccessibility();
        $accessibility2->setId(Uuid::v4());
        $accessibility2->setName('Elevadores');
        $accessibility2->setDescription('Elevadores adaptados para acessibilidade');

        $spaceType = new SpaceType();
        $spaceType->setId(Uuid::v4());
        $spaceType->setName('Espaço Cultural');

        $entityAssociation = new EntityAssociation();
        $entityAssociation->setId(Uuid::v4());
        $entityAssociation->setSpace($space);

        $this->assertNull($space->getId());
        $this->assertNull($space->getName());
        $this->assertNull($space->getImage());
        $this->assertNull($space->getCoverImage());
        $this->assertNull($space->getShortDescription());
        $this->assertNull($space->getLongDescription());
        $this->assertNull($space->getSite());
        $this->assertNull($space->getEmail());
        $this->assertNull($space->getPhoneNumber());
        $this->assertNull($space->getAddress());
        $this->assertNull($space->getParent());
        $this->assertCount(0, $space->getTags());
        $this->assertNotNull($space->getCreatedAt());
        $this->assertNull($space->getUpdatedAt());
        $this->assertNull($space->getDeletedAt());

        $id = new Uuid('8d74efa8-fd92-4d4d-9e5f-7fafa674cf55');
        $extraField = [
            'type' => 'Instituição Cultural',
            'description' => 'A Secretaria da Cultura (SECULT) é responsável por fomentar a arte e a cultura no estado, organizando eventos e oferecendo apoio a iniciativas locais.',
            'location' => 'Complexo Estação das Artes - R. Dr. João Moreira, 540 - Centro, Fortaleza - CE, 60030-000',
        ];
        $tags = new ArrayCollection([
            $tag1,
            $tag2,
        ]);

        $accessibilities = new ArrayCollection([
            $accessibility1,
            $accessibility2,
        ]);

        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTime();
        $deletedAt = new DateTime();

        $activityArea1 = new ActivityArea();
        $activityArea1->setId(Uuid::v4());
        $activityArea1->setName('Teatro');

        $activityArea2 = new ActivityArea();
        $activityArea2->setId(Uuid::v4());
        $activityArea2->setName('Música');

        $space->setId($id);
        $space->setName('Casa do Cantador');
        $space->setImage('https://url-image.com.br');
        $space->setCoverImage('https://url-cover.com.br');
        $space->setShortDescription('Espaço cultural para eventos');
        $space->setLongDescription('Um espaço dedicado à cultura popular, promovendo eventos musicais e teatrais.');
        $space->setSite('https://casadocantador.com.br');
        $space->setEmail('contato@casadocantador.com.br');
        $space->setPhoneNumber('+55 85 99999-9999');
        $space->setMaxCapacity(500);
        $space->setIsAccessible(true);
        $space->setIsDraft(false);

        $space->setCreatedBy($agent);
        $space->setParent($spaceParent);
        $space->setAddress($spaceAddress);
        $space->setExtraFields($extraField);
        $space->setTags($tags);
        $space->setAccessibilities($accessibilities);
        $space->setSpaceType($spaceType);
        $space->setEntityAssociation($entityAssociation);
        $space->setCreatedAt($createdAt);
        $space->setUpdatedAt($updatedAt);
        $space->setDeletedAt($deletedAt);
        $space->addActivityArea($activityArea1);
        $space->addActivityArea($activityArea2);

        $this->assertEquals('Casa do Cantador', $space->getName());
        $this->assertEquals('https://url-image.com.br', $space->getImage());
        $this->assertEquals('https://url-cover.com.br', $space->getCoverImage());
        $this->assertEquals('Espaço cultural para eventos', $space->getShortDescription());
        $this->assertEquals('Um espaço dedicado à cultura popular, promovendo eventos musicais e teatrais.', $space->getLongDescription());
        $this->assertEquals('https://casadocantador.com.br', $space->getSite());
        $this->assertEquals('contato@casadocantador.com.br', $space->getEmail());
        $this->assertEquals('+55 85 99999-9999', $space->getPhoneNumber());
        $this->assertEquals(500, $space->getMaxCapacity());
        $this->assertTrue($space->isAccessible());
        $this->assertFalse($space->isDraft());

        $this->assertCount(2, $space->getActivityAreas());
        $this->assertContains($activityArea1, $space->getActivityAreas());
        $this->assertContains($activityArea2, $space->getActivityAreas());

        $this->assertEquals($id, $space->getId());
        $this->assertInstanceOf(Uuid::class, $space->getId());

        $this->assertEquals('Casa do Cantador', $space->getName());
        $this->assertIsString($space->getName());

        $this->assertEquals($agent, $space->getCreatedBy());
        $this->assertInstanceOf(Agent::class, $space->getCreatedBy());

        $this->assertEquals('https://url-image.com.br', $space->getImage());
        $this->assertIsString($space->getImage());

        $this->assertEquals($spaceParent, $space->getParent());
        $this->assertInstanceOf(Space::class, $space->getParent());

        $this->assertEquals($extraField, $space->getExtraFields());
        $this->assertIsArray($space->getExtraFields());

        $this->assertEquals($spaceAddress, $space->getAddress());
        $this->assertInstanceOf(SpaceAddress::class, $space->getAddress());

        $this->assertEquals($entityAssociation, $space->getEntityAssociation());
        $this->assertInstanceOf(EntityAssociation::class, $space->getEntityAssociation());

        $this->assertEquals($tags, $space->getTags());
        $this->assertInstanceOf(Collection::class, $space->getTags());

        $this->assertEquals($accessibilities, $space->getAccessibilities());
        $this->assertInstanceOf(Collection::class, $space->getAccessibilities());

        $this->assertEquals($spaceType, $space->getSpaceType());
        $this->assertInstanceOf(SpaceType::class, $space->getSpaceType());

        $this->assertEquals($createdAt, $space->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $space->getCreatedAt());

        $this->assertEquals($updatedAt, $space->getUpdatedAt());
        $this->assertInstanceOf(DateTime::class, $space->getUpdatedAt());

        $space->removeActivityArea($activityArea1);
        $this->assertCount(1, $space->getActivityAreas());
        $this->assertNotContains($activityArea1, $space->getActivityAreas());

        $this->assertEquals([
            'id' => $id->toString(),
            'name' => 'Casa do Cantador',
            'image' => 'https://url-image.com.br',
            'coverImage' => 'https://url-cover.com.br',
            'shortDescription' => 'Espaço cultural para eventos',
            'longDescription' => 'Um espaço dedicado à cultura popular, promovendo eventos musicais e teatrais.',
            'site' => 'https://casadocantador.com.br',
            'email' => 'contato@casadocantador.com.br',
            'phoneNumber' => '+55 85 99999-9999',
            'maxCapacity' => 500,
            'isAccessible' => true,
            'isDraft' => false,
            'createdBy' => '95f91eb5-cb62-4a7b-b677-8486d2a0763a',
            'parent' => '8e3e976d-0fc0-443e-bdd2-2b4d83da004f',
            'address' => $spaceAddress->toArray(),
            'extraFields' => $extraField,
            'activityAreas' => array_map(fn (ActivityArea $area) => $area->toArray(), $space->getActivityAreas()->toArray()),
            'entityAssociation' => $entityAssociation->toArray(),
            'tags' => array_map(fn (Tag $tag) => $tag->toArray(), $tags->toArray()),
            'accessibilities' => array_map(fn (ArchitecturalAccessibility $accessibility) => $accessibility->toArray(), $accessibilities->toArray()),
            'spaceType' => $spaceType->toArray(),
            'socialNetworks' => [],
            'createdAt' => $createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $updatedAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $deletedAt->format(DateFormatHelper::DEFAULT_FORMAT),
        ], $space->toArray());
    }
}
