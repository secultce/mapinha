<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Faq;
use App\Helper\DateFormatHelper;
use App\Tests\AbstractApiTestCase;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class FaqEntityTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromFaqEntityShouldBeSuccessful(): void
    {
        $faq = new Faq();

        $id = Uuid::v4();
        $question = 'Fulano';
        $answer = 'Ditau';
        $active = false;
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTime();

        $faq->setId($id);
        $faq->setQuestion($question);
        $faq->setAnswer($answer);
        $faq->setActive($active);
        $faq->setCreatedAt($createdAt);

        $this->assertEquals($question, $faq->getQuestion());
        $this->assertIsString($faq->getQuestion());

        $this->assertEquals($answer, $faq->getAnswer());
        $this->assertIsString($faq->getAnswer());

        $this->assertEquals($active, $faq->isActive());
        $this->assertIsBool($faq->isActive());

        $this->assertEquals($createdAt, $faq->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $faq->getCreatedAt());

        $this->assertFalse($faq->isActive());

        $this->assertEquals([
            'id' => $id->toRfc4122(),
            'question' => $question,
            'answer' => $answer,
            'active' => $active,
            'createdAt' => $createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => null,
        ], $faq->toArray());

        $faq->setUpdatedAt($updatedAt);

        $this->assertEquals([
            'id' => $id->toRfc4122(),
            'question' => $question,
            'answer' => $answer,
            'active' => $active,
            'createdAt' => $createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $updatedAt->format(DateFormatHelper::DEFAULT_FORMAT),
        ], $faq->toArray());
    }
}
