<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250317215130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table entity association';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<EOQ
            CREATE TABLE entity_association (
                id UUID NOT NULL, 
                agent_id UUID, 
                event_id UUID, 
                initiative_id UUID, 
                opportunity_id UUID, 
                organization_id UUID, 
                space_id UUID, 
                with_agent BOOLEAN DEFAULT FALSE, 
                with_event BOOLEAN DEFAULT FALSE, 
                with_initiative BOOLEAN DEFAULT FALSE, 
                with_space BOOLEAN DEFAULT FALSE, 
                with_opportunity BOOLEAN DEFAULT FALSE, 
                with_organization BOOLEAN DEFAULT FALSE,
                PRIMARY KEY(id),
                CONSTRAINT only_one_entity CHECK (
                    (agent_id IS NOT NULL AND event_id IS NULL AND initiative_id IS NULL AND opportunity_id IS NULL AND organization_id IS NULL AND space_id IS NULL ) OR
                    (agent_id IS NULL AND event_id IS NOT NULL AND initiative_id IS NULL AND opportunity_id IS NULL AND organization_id IS NULL AND space_id IS NULL) OR
                    (agent_id IS NULL AND event_id IS NULL AND initiative_id IS NOT NULL AND opportunity_id IS NULL AND organization_id IS NULL AND space_id IS NULL) OR
                    (agent_id IS NULL AND event_id IS NULL AND initiative_id IS NULL AND opportunity_id IS NOT NULL AND organization_id IS NULL AND space_id IS NULL) OR
                    (agent_id IS NULL AND event_id IS NULL AND initiative_id IS NULL AND opportunity_id IS NULL AND organization_id IS NOT NULL AND space_id IS NULL) OR
                    (agent_id IS NULL AND event_id IS NULL AND initiative_id IS NULL AND opportunity_id IS NULL AND organization_id IS NULL AND space_id IS NOT NULL)
                )
            )
            EOQ;
        $this->addSql($sql);

        $this->addSql('COMMENT ON COLUMN entity_association.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN entity_association.agent_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN entity_association.event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN entity_association.initiative_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN entity_association.opportunity_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN entity_association.organization_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN entity_association.space_id IS \'(DC2Type:uuid)\'');

        $this->addSql('CREATE INDEX idx_entity_association_agent_id ON entity_association (agent_id)');
        $this->addSql('CREATE INDEX idx_entity_association_event_id ON entity_association (event_id)');
        $this->addSql('CREATE INDEX idx_entity_association_initiative_id ON entity_association (initiative_id)');
        $this->addSql('CREATE INDEX idx_entity_association_opportunity_id ON entity_association (opportunity_id)');
        $this->addSql('CREATE INDEX idx_entity_association_organization_id ON entity_association (organization_id)');
        $this->addSql('CREATE INDEX idx_entity_association_space_id ON entity_association (space_id)');

        $this->addSql('ALTER TABLE entity_association ADD CONSTRAINT fk_entity_association_agent_id_agent FOREIGN KEY (agent_id) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE entity_association ADD CONSTRAINT fk_entity_association_event_id_event FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE entity_association ADD CONSTRAINT fk_entity_association_initiative_id_initiative FOREIGN KEY (initiative_id) REFERENCES initiative (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE entity_association ADD CONSTRAINT fk_entity_association_opportunity_id_opportunity FOREIGN KEY (opportunity_id) REFERENCES opportunity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE entity_association ADD CONSTRAINT fk_entity_association_organization_id_organization FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE entity_association ADD CONSTRAINT fk_entity_association_space_id_space FOREIGN KEY (space_id) REFERENCES space (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE entity_association');
    }
}
