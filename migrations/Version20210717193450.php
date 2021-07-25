<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210717193450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add tables for the CharacterSheet context';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE characters (
                id CHARACTER(36) PRIMARY KEY,
                date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                workspace VARCHAR(255),
                name VARCHAR(255),
                experience INT
              )
        ');

        $this->addSql('
            CREATE INDEX character_name ON characters(name)
        ');

        $this->addSql('
            CONSTRAINT constraint_character_name UNIQUE characters(name)
        ');

        $this->addSql('
            CREATE TABLE properties (
                character_id CHARACTER(36), 
                date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                name VARCHAR(255), 
                type VARCHAR(255), 
                level INT
              )
        ');

        $this->addSql('
            CREATE INDEX fk_character_id ON properties(character_id)
        ');

        $this->addSql('
            CREATE INDEX type ON properties(type)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE characters');
        $this->addSql('DROP TABLE properties');
    }
}
