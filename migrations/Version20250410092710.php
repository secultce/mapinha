<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250410092710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alter the inscription_opportunity table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE inscription_opportunity ADD COLUMN organization_id UUID NULL');
        $this->addSql('ALTER TABLE inscription_opportunity ALTER COLUMN agent_id DROP NOT NULL');
        $this->addSql('ALTER TABLE inscription_opportunity ADD CONSTRAINT fk_inscription_opportunity_organization_id_organization FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('COMMENT ON COLUMN inscription_opportunity.organization_id IS \'(DC2Type:uuid)\'');

        $this->addSql('ALTER TABLE inscription_opportunity ADD CONSTRAINT unique_organization_opportunity UNIQUE (organization_id, opportunity_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE inscription_opportunity ALTER COLUMN agent_id SET NOT NULL');
        $this->addSql('ALTER TABLE inscription_opportunity DROP CONSTRAINT unique_organization_opportunity');
        $this->addSql('ALTER TABLE inscription_opportunity DROP CONSTRAINT fk_inscription_opportunity_organization_id_organization');
        $this->addSql('ALTER TABLE inscription_opportunity REMOVE COLUMN organization_id');
    }
}
