<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250318231529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table cultural_language';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE cultural_language (id UUID NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE event_cultural_languages (event_id UUID NOT NULL, cultural_language_id UUID NOT NULL, PRIMARY KEY(event_id, cultural_language_id))');

        $this->addSql('CREATE INDEX idx_event_cultural_languages_event_id ON event_cultural_languages (event_id)');
        $this->addSql('CREATE INDEX idx_event_cultural_languages_cultural_language_id ON event_cultural_languages (cultural_language_id)');

        $this->addSql('ALTER TABLE event_cultural_languages ADD CONSTRAINT fk_event_cultural_languages_event_id FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_cultural_languages ADD CONSTRAINT fk_event_cultural_languages_cultural_language_id FOREIGN KEY (cultural_language_id) REFERENCES cultural_language (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event_cultural_languages');
        $this->addSql('DROP TABLE cultural_language');
    }
}
