<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250411205715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table invite';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
        CREATE TABLE invite (
            id UUID NOT NULL, 
            agent_id UUID DEFAULT NULL, 
            organization_id UUID NOT NULL, 
            token UUID NOT NULL, 
            expiration_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            PRIMARY KEY(id))
        ');

        $this->addSql('COMMENT ON COLUMN invite.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invite.agent_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invite.organization_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invite.token IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invite.expiration_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('ALTER TABLE invite ADD CONSTRAINT fk_invite_agent_id_agent FOREIGN KEY (agent_id) REFERENCES agent (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invite ADD CONSTRAINT fk_invite_organization_id_organization FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE invite');
    }
}
