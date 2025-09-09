<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250331191648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the column is_draft on the table Space';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE Space ADD is_draft BOOLEAN DEFAULT \'true\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE Space DROP is_draft');
    }
}
