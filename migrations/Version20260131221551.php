<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260131221551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create agent_photo junction table for Agent portfolio';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE agent_photo (
            agent_id UUID NOT NULL,
            photo_id UUID NOT NULL,
            PRIMARY KEY(agent_id, photo_id)
        )');

        $this->addSql('CREATE INDEX IDX_AGENT_PHOTO_AGENT_ID ON agent_photo (agent_id)');
        $this->addSql('CREATE INDEX IDX_AGENT_PHOTO_PHOTO_ID ON agent_photo (photo_id)');
        $this->addSql("COMMENT ON COLUMN agent_photo.agent_id IS '(DC2Type:uuid)'");
        $this->addSql("COMMENT ON COLUMN agent_photo.photo_id IS '(DC2Type:uuid)'");

        $this->addSql('ALTER TABLE agent_photo ADD CONSTRAINT FK_AGENT_PHOTO_AGENT_ID FOREIGN KEY (agent_id) REFERENCES agent (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_photo ADD CONSTRAINT FK_AGENT_PHOTO_PHOTO_ID FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE agent_photo DROP CONSTRAINT FK_AGENT_PHOTO_AGENT_ID');
        $this->addSql('ALTER TABLE agent_photo DROP CONSTRAINT FK_AGENT_PHOTO_PHOTO_ID');
        $this->addSql('DROP TABLE agent_photo');
    }
}
