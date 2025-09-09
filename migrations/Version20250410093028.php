<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250410093028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alter inscription_phase table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE inscription_phase ADD COLUMN organization_id UUID NULL');
        $this->addSql('ALTER TABLE inscription_phase ALTER COLUMN agent_id DROP NOT NULL');

        $this->addSql('CREATE INDEX IDX_E3F482863414711B ON inscription_phase (organization_id)');

        $this->addSql('COMMENT ON COLUMN inscription_phase.organization_id IS \'(DC2Type:uuid)\'');

        $this->addSql('ALTER TABLE inscription_phase ADD CONSTRAINT fk_inscription_phase_organization_id_organization FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE inscription_phase ALTER COLUMN agent_id SET NOT NULL');
        $this->addSql('ALTER TABLE inscription_phase DROP CONSTRAINT fk_inscription_phase_organization_id_organization');
        $this->addSql('ALTER TABLE inscription_phase REMOVE COLUMN organization_id');
    }
}
