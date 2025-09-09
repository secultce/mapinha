<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250414153354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add extra_fields column to inscription_phase table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE inscription_phase ADD extra_fields JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE inscription_phase DROP extra_fields');
    }
}
