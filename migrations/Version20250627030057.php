<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250627030057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow null values in end_date column of event table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event ALTER COLUMN end_date DROP NOT NULL');
        $this->addSql('ALTER TABLE event ALTER COLUMN max_capacity DROP NOT NULL');
        $this->addSql('ALTER TABLE event ALTER COLUMN accessible_audio DROP NOT NULL');
        $this->addSql('ALTER TABLE event ALTER COLUMN accessible_libras DROP NOT NULL');
        $this->addSql('ALTER TABLE event ALTER COLUMN free DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event ALTER COLUMN end_date SET NOT NULL');
        $this->addSql('ALTER TABLE event ALTER COLUMN max_capacity SET NOT NULL');
        $this->addSql('ALTER TABLE event ALTER COLUMN accessible_audio SET NOT NULL');
        $this->addSql('ALTER TABLE event ALTER COLUMN accessible_libras SET NOT NULL');
        $this->addSql('ALTER TABLE event ALTER COLUMN free SET NOT NULL');
    }
}
