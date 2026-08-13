<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260106232201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify Opportunity Entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE opportunity ADD description VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE opportunity DROP description');
    }
}
