<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260111085902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify Agent Entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE agent ADD fiscal_code VARCHAR(30)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE agent DROP fiscal_code');
    }
}
