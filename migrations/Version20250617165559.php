<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250617165559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the column email in the table invite and remove the column token';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE invite ADD COLUMN email VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE invite DROP COLUMN token');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE invite DROP COLUMN email');
        $this->addSql('ALTER TABLE invite ADD COLUMN token UUID NOT NULL');
    }
}
