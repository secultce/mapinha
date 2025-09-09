<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250317183328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create table inscription_event';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE inscription_event (id UUID NOT NULL, agent_id UUID NOT NULL, event_id UUID NOT NULL, status SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_inscription_event_agent_id ON inscription_event (agent_id)');
        $this->addSql('CREATE INDEX idx_inscription_event_event_id ON inscription_event (event_id)');
        $this->addSql('COMMENT ON COLUMN inscription_event.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN inscription_event.agent_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN inscription_event.event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN inscription_event.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE inscription_event ADD CONSTRAINT fk_inscription_event_agent_id_agent FOREIGN KEY (agent_id) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inscription_event ADD CONSTRAINT fk_inscription_event_event_id_event FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE inscription_event ADD CONSTRAINT unique_agent_event UNIQUE (agent_id, event_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE inscription_event DROP CONSTRAINT unique_agent_event');
        $this->addSql('ALTER TABLE inscription_event DROP CONSTRAINT fk_inscription_event_agent_id_agent');
        $this->addSql('ALTER TABLE inscription_event DROP CONSTRAINT fk_inscription_event_event_id_event');
        $this->addSql('DROP TABLE inscription_event');
    }
}
