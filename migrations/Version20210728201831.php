<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210728201831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create property_definitions';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE property_definitions (
                name VARCHAR(255),
                type VARCHAR(255)
              )
        ');

        $this->addSql('
            CREATE INDEX property_type ON property_definitions(type)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE property_definitions');
    }
}
