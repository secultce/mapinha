<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250401201146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Type column to the Organization table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization ADD type VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization DROP type');
    }
}
