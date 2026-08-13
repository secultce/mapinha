<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260121120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create photo and space_photo tables for space portfolio feature';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE photo (
            id UUID NOT NULL,
            image VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('COMMENT ON COLUMN photo.id IS \'(DC2Type:uuid)\'');

        $this->addSql('CREATE TABLE space_photo (
            space_id UUID NOT NULL,
            photo_id UUID NOT NULL,
            PRIMARY KEY(space_id, photo_id)
        )');
        $this->addSql('CREATE INDEX IDX_SPACE_PHOTO_SPACE_ID ON space_photo (space_id)');
        $this->addSql('CREATE INDEX IDX_SPACE_PHOTO_PHOTO_ID ON space_photo (photo_id)');
        $this->addSql('COMMENT ON COLUMN space_photo.space_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN space_photo.photo_id IS \'(DC2Type:uuid)\'');

        $this->addSql('ALTER TABLE space_photo ADD CONSTRAINT FK_SPACE_PHOTO_SPACE FOREIGN KEY (space_id) REFERENCES space (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE space_photo ADD CONSTRAINT FK_SPACE_PHOTO_PHOTO FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE space_photo DROP CONSTRAINT FK_SPACE_PHOTO_SPACE');
        $this->addSql('ALTER TABLE space_photo DROP CONSTRAINT FK_SPACE_PHOTO_PHOTO');
        $this->addSql('DROP TABLE space_photo');
        $this->addSql('DROP TABLE photo');
    }
}
