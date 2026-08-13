<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260220204355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add opportunity cover image';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE opportunity ADD cover_image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE opportunity DROP cover_image');
    }
}
