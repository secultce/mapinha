<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260227003936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add cover image to agent';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE agent ADD cover_image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE agent DROP cover_image');
    }
}
