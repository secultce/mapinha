<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250402141131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add status column to the user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_user ADD status VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_user DROP status');
    }
}
