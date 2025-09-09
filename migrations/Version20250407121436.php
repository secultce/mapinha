<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250407121436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tabela account_event';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
        CREATE TABLE account_event (
            id UUID NOT NULL, 
            user_id UUID NOT NULL, 
            type INT NOT NULL, 
            token UUID NOT NULL, 
            expiration_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            PRIMARY KEY(id))
        ');

        $this->addSql('COMMENT ON COLUMN event_activity.event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN account_event.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN account_event.token IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN account_event.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN account_event.expiration_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('ALTER TABLE account_event ADD CONSTRAINT fk_account_event_user_id_app_user FOREIGN KEY (user_id) REFERENCES "app_user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE account_event');
    }
}
