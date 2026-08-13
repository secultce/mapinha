<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260113210410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update user cover image';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_user ADD cover_image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "app_user" DROP cover_image');
    }
}
