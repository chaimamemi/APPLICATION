<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228171649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844AAEB1DB6');
        $this->addSql('ALTER TABLE health_professional DROP FOREIGN KEY FK_40A5C329B125F06');
        $this->addSql('ALTER TABLE health_professional DROP FOREIGN KEY FK_40A5C3263DBB69');
        $this->addSql('ALTER TABLE health_professional DROP FOREIGN KEY FK_40A5C3287F4FB17');
        $this->addSql('ALTER TABLE professional_access DROP FOREIGN KEY FK_46D759882B46C3E0');
        $this->addSql('ALTER TABLE professional_access DROP FOREIGN KEY FK_46D759889D86650F');
        $this->addSql('DROP TABLE health_professional');
        $this->addSql('DROP TABLE professional_access');
        $this->addSql('DROP INDEX IDX_FE38F844AAEB1DB6 ON appointment');
        $this->addSql('ALTER TABLE appointment DROP professional_user_id_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE health_professional (id INT AUTO_INCREMENT NOT NULL, hospital_id INT DEFAULT NULL, doctor_id INT DEFAULT NULL, emergency_team_id INT DEFAULT NULL, specialty VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, dashboard_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, health_role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_40A5C3263DBB69 (hospital_id), INDEX IDX_40A5C3287F4FB17 (doctor_id), INDEX IDX_40A5C329B125F06 (emergency_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE professional_access (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, patient_user_id_id INT DEFAULT NULL, access_level VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_46D759889D86650F (user_id_id), INDEX IDX_46D759882B46C3E0 (patient_user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE health_professional ADD CONSTRAINT FK_40A5C329B125F06 FOREIGN KEY (emergency_team_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE health_professional ADD CONSTRAINT FK_40A5C3263DBB69 FOREIGN KEY (hospital_id) REFERENCES hospital (id)');
        $this->addSql('ALTER TABLE health_professional ADD CONSTRAINT FK_40A5C3287F4FB17 FOREIGN KEY (doctor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE professional_access ADD CONSTRAINT FK_46D759882B46C3E0 FOREIGN KEY (patient_user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE professional_access ADD CONSTRAINT FK_46D759889D86650F FOREIGN KEY (user_id_id) REFERENCES health_professional (id)');
        $this->addSql('ALTER TABLE appointment ADD professional_user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844AAEB1DB6 FOREIGN KEY (professional_user_id_id) REFERENCES health_professional (id)');
        $this->addSql('CREATE INDEX IDX_FE38F844AAEB1DB6 ON appointment (professional_user_id_id)');
    }
}
