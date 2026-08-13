<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260123082601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create relationship between ActivityArea with Organization';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE activity_area_organizations (activity_area_id UUID NOT NULL, organization_id UUID NOT NULL, PRIMARY KEY(activity_area_id, organization_id))');

        $this->addSql('CREATE INDEX IDX_F15AB7E8BD5D36AA ON activity_area_organizations (activity_area_id)');
        $this->addSql('CREATE INDEX IDX_F15AB7E8235753BB ON activity_area_organizations (organization_id)');

        $this->addSql('ALTER TABLE activity_area_organizations ADD CONSTRAINT fk_activity_area_organizations_by_activity_area_id FOREIGN KEY (activity_area_id) REFERENCES activity_area (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_area_organizations ADD CONSTRAINT fk_activity_area_organizations_by_organization_id FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE activity_area_organizations');
    }
}
