<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Agent;
use App\Entity\InscriptionPhase;
use App\Entity\InscriptionPhaseReview;
use App\Helper\DateFormatHelper;
use App\Tests\AbstractApiTestCase;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class InscriptionPhaseReviewTest extends AbstractApiTestCase
{
    public function testGetters(): void
    {
        $inscriptionPhase = new InscriptionPhase();
        $inscriptionPhaseId = Uuid::v4();
        $inscriptionPhase->setId($inscriptionPhaseId);

        $reviewerId = Uuid::v4();
        $reviewer = new Agent();
        $reviewer->setId($reviewerId);

        $inscriptionPhaseReview = new InscriptionPhaseReview();
        $id = Uuid::v4();
        $result = [
            'lorem' => 'ipsum',
        ];
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTime();
        $deletedAt = new DateTime();

        $inscriptionPhaseReview->setId($id);
        $inscriptionPhaseReview->setResult($result);
        $inscriptionPhaseReview->setCreatedAt($createdAt);
        $inscriptionPhaseReview->setUpdatedAt($updatedAt);
        $inscriptionPhaseReview->setDeletedAt($deletedAt);
        $inscriptionPhaseReview->setInscriptionPhase($inscriptionPhase);
        $inscriptionPhaseReview->setReviewer($reviewer);

        $this->assertEquals($id, $inscriptionPhaseReview->getId());
        $this->assertEquals($inscriptionPhase->getId(), $inscriptionPhaseReview->getInscriptionPhase()->getId());
        $this->assertEquals($reviewer->getId(), $inscriptionPhaseReview->getReviewer()->getId());
        $this->assertEquals($result, $inscriptionPhaseReview->getResult());
        $this->assertSame($createdAt, $inscriptionPhaseReview->getCreatedAt());
        $this->assertSame($updatedAt, $inscriptionPhaseReview->getUpdatedAt());
        $this->assertSame($deletedAt, $inscriptionPhaseReview->getDeletedAt());
    }

    public function testToArray(): void
    {
        $inscriptionPhase = new InscriptionPhase();
        $inscriptionPhaseId = Uuid::v4();
        $inscriptionPhase->setId($inscriptionPhaseId);

        $reviewerId = Uuid::v4();
        $reviewer = new Agent();
        $reviewer->setId($reviewerId);

        $inscriptionPhaseReview = new InscriptionPhaseReview();
        $id = Uuid::v4();
        $result = [
            'lorem' => 'ipsum',
        ];
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTime();
        $deletedAt = new DateTime();

        $inscriptionPhaseReview->setId($id);
        $inscriptionPhaseReview->setResult($result);
        $inscriptionPhaseReview->setCreatedAt($createdAt);
        $inscriptionPhaseReview->setUpdatedAt($updatedAt);
        $inscriptionPhaseReview->setDeletedAt($deletedAt);
        $inscriptionPhaseReview->setInscriptionPhase($inscriptionPhase);
        $inscriptionPhaseReview->setReviewer($reviewer);

        $this->assertEquals(
            [
                'id' => $id->toRfc4122(),
                'inscriptionPhase' => $inscriptionPhase->getId()->toRfc4122(),
                'reviewer' => $reviewer->getId()->toRfc4122(),
                'result' => [
                    'lorem' => 'ipsum',
                ],
                'createdAt' => $createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
                'updatedAt' => $updatedAt->format(DateFormatHelper::DEFAULT_FORMAT),
                'deletedAt' => $deletedAt->format(DateFormatHelper::DEFAULT_FORMAT),
            ],
            $inscriptionPhaseReview->toArray()
        );
    }
}
