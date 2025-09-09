<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250724205220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create EventType Entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE event_type (id UUID NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN event_type.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE event ADD event_type_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD format INT NOT NULL');
        $this->addSql('ALTER TABLE event DROP type');
        $this->addSql('COMMENT ON COLUMN event.event_type_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT fk_event_event_type_id_event_type FOREIGN KEY (event_type_id) REFERENCES event_type (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7401B253C ON event (event_type_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT fk_event_event_type_id_event_type');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('ALTER TABLE event ADD type SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE event DROP event_type_id');
        $this->addSql('ALTER TABLE event DROP format');
    }
}
