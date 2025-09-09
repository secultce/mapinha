<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250429093112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alter initiative table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE initiative ADD COLUMN organization_from_id UUID NULL');
        $this->addSql('ALTER TABLE initiative ADD COLUMN organization_to_id UUID NULL');

        $this->addSql('CREATE INDEX IDX_E3F482863414712A ON initiative (organization_from_id)');
        $this->addSql('CREATE INDEX IDX_E3F482863414712B ON initiative (organization_to_id)');

        $this->addSql('COMMENT ON COLUMN initiative.organization_from_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN initiative.organization_to_id IS \'(DC2Type:uuid)\'');

        $this->addSql('ALTER TABLE initiative ADD CONSTRAINT fk_initiative_organization_to_id_organization FOREIGN KEY (organization_to_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE initiative ADD CONSTRAINT fk_initiative_organization_from_id_organization FOREIGN KEY (organization_from_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE initiative DROP CONSTRAINT fk_initiative_organization_to_id_organization');
        $this->addSql('ALTER TABLE initiative DROP CONSTRAINT fk_initiative_organization_from_id_organization');
        $this->addSql('ALTER TABLE initiative REMOVE COLUMN organization_to_id');
        $this->addSql('ALTER TABLE initiative REMOVE COLUMN organization_from_id');
    }
}
