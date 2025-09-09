<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250722132634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Cultural Function Entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE agent_cultural_function (agent_id UUID NOT NULL, cultural_function_id UUID NOT NULL, PRIMARY KEY(agent_id, cultural_function_id))');
        $this->addSql('CREATE INDEX IDX_A4F1AC1B3414710B ON agent_cultural_function (agent_id)');
        $this->addSql('CREATE INDEX IDX_A4F1AC1BE9A0A668 ON agent_cultural_function (cultural_function_id)');
        $this->addSql('COMMENT ON COLUMN agent_cultural_function.agent_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN agent_cultural_function.cultural_function_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE cultural_function (id UUID NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN cultural_function.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE agent_cultural_function ADD CONSTRAINT fk_agent_cultual_function_agent FOREIGN KEY (agent_id) REFERENCES agent (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_cultural_function ADD CONSTRAINT fk_agent_cultural_function_cultural_function FOREIGN KEY (cultural_function_id) REFERENCES cultural_function (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE agent_cultural_function DROP CONSTRAINT fk_agent_cultual_function_agent');
        $this->addSql('ALTER TABLE agent_cultural_function DROP CONSTRAINT fk_agent_cultural_function_cultural_function');
        $this->addSql('DROP TABLE agent_cultural_function');
        $this->addSql('DROP TABLE cultural_function');
    }
}
