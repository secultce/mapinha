<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250617191803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the column draft on the table event';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event ADD draft BOOLEAN DEFAULT \'true\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event DROP draft');
    }
}
