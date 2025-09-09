<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250423151409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set token and expiration_at nullable in account_event table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE account_event ALTER COLUMN token DROP NOT NULL');
        $this->addSql('ALTER TABLE account_event ALTER COLUMN expiration_at DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $defaultToken = '9a6c79f3-dfcb-4e3a-99a7-2e53bd7c3e8a';

        $this->addSql('UPDATE account_event SET token = :defaultToken WHERE token IS NULL', [
            'defaultToken' => $defaultToken,
        ]);
        $this->addSql('UPDATE account_event SET expiration_at = now() WHERE expiration_at IS NULL');

        $this->addSql('ALTER TABLE account_event ALTER COLUMN token SET NOT NULL');
        $this->addSql('ALTER TABLE account_event ALTER COLUMN expiration_at SET NOT NULL');
    }
}
