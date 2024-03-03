<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303122013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medication (id INT AUTO_INCREMENT NOT NULL, biological_data_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, name_medication VARCHAR(255) NOT NULL, medical_note VARCHAR(255) NOT NULL, dosage VARCHAR(255) NOT NULL, INDEX IDX_5AEE5B7050DE7A58 (biological_data_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medication ADD CONSTRAINT FK_5AEE5B7050DE7A58 FOREIGN KEY (biological_data_id) REFERENCES biological_data (id)');
        $this->addSql('ALTER TABLE biological_data DROP medication');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medication DROP FOREIGN KEY FK_5AEE5B7050DE7A58');
        $this->addSql('DROP TABLE medication');
        $this->addSql('ALTER TABLE biological_data ADD medication VARCHAR(255) NOT NULL');
    }
}
