<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260211185521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add long description and cover image to organization';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization ADD long_description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE organization ADD cover_image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization DROP long_description');
        $this->addSql('ALTER TABLE organization DROP cover_image');
    }
}
