<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305225428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE intervention_action (id INT AUTO_INCREMENT NOT NULL, patient_id_id INT DEFAULT NULL, emergency_team_id_id INT DEFAULT NULL, alertid_id INT DEFAULT NULL, date_time DATETIME NOT NULL, INDEX IDX_AD6CAEB7EA724598 (patient_id_id), INDEX IDX_AD6CAEB7AC5689CA (emergency_team_id_id), INDEX IDX_AD6CAEB7ADD13CC8 (alertid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE intervention_action ADD CONSTRAINT FK_AD6CAEB7EA724598 FOREIGN KEY (patient_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intervention_action ADD CONSTRAINT FK_AD6CAEB7AC5689CA FOREIGN KEY (emergency_team_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intervention_action ADD CONSTRAINT FK_AD6CAEB7ADD13CC8 FOREIGN KEY (alertid_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention_action DROP FOREIGN KEY FK_AD6CAEB7EA724598');
        $this->addSql('ALTER TABLE intervention_action DROP FOREIGN KEY FK_AD6CAEB7AC5689CA');
        $this->addSql('ALTER TABLE intervention_action DROP FOREIGN KEY FK_AD6CAEB7ADD13CC8');
        $this->addSql('DROP TABLE intervention_action');
    }
}
