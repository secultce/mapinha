<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250318212851 extends AbstractMigration
{
    public const array TABLES = [
        'agent',
        'event',
        'initiative',
        'opportunity',
        'organization',
        'space',
    ];

    public function getDescription(): string
    {
        return 'Add social networks on tables';
    }

    public function up(Schema $schema): void
    {
        foreach (self::TABLES as $table) {
            $this->addSql(sprintf('ALTER TABLE %s ADD social_networks JSONB DEFAULT NULL', $table));
        }
    }

    public function down(Schema $schema): void
    {
        foreach (self::TABLES as $table) {
            $this->addSql(sprintf('ALTER TABLE %s DROP social_networks', $table));
        }
    }
}
