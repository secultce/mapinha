<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250416230106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add region column to the state table and populate it with values';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE state ADD region VARCHAR(20) NULL');

        $this->addSql("UPDATE state SET region = 'Norte' WHERE acronym IN ('AC', 'AP', 'AM', 'PA', 'RO', 'RR', 'TO')");
        $this->addSql("UPDATE state SET region = 'Nordeste' WHERE acronym IN ('AL', 'BA', 'CE', 'MA', 'PB', 'PE', 'PI', 'RN', 'SE')");
        $this->addSql("UPDATE state SET region = 'Sudeste' WHERE acronym IN ('ES', 'MG', 'RJ', 'SP')");
        $this->addSql("UPDATE state SET region = 'Sul' WHERE acronym IN ('PR', 'RS', 'SC')");
        $this->addSql("UPDATE state SET region = 'Centro-Oeste' WHERE acronym IN ('DF', 'GO', 'MS', 'MT')");

        $this->addSql('ALTER TABLE state ALTER COLUMN region SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE state DROP region');
    }
}
