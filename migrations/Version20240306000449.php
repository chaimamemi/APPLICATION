<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306000449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention_action DROP FOREIGN KEY FK_AD6CAEB7ADD13CC8');
        $this->addSql('ALTER TABLE intervention_action DROP FOREIGN KEY FK_AD6CAEB7EA724598');
        $this->addSql('ALTER TABLE intervention_action DROP FOREIGN KEY FK_AD6CAEB7AC5689CA');
        $this->addSql('DROP INDEX IDX_AD6CAEB7EA724598 ON intervention_action');
        $this->addSql('DROP INDEX IDX_AD6CAEB7AC5689CA ON intervention_action');
        $this->addSql('DROP INDEX IDX_AD6CAEB7ADD13CC8 ON intervention_action');
        $this->addSql('ALTER TABLE intervention_action ADD patient_id INT DEFAULT NULL, ADD emergency_team_id INT DEFAULT NULL, ADD alert_id INT DEFAULT NULL, DROP patient_id_id, DROP emergency_team_id_id, DROP alertid_id');
        $this->addSql('ALTER TABLE intervention_action ADD CONSTRAINT FK_AD6CAEB76B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intervention_action ADD CONSTRAINT FK_AD6CAEB79B125F06 FOREIGN KEY (emergency_team_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intervention_action ADD CONSTRAINT FK_AD6CAEB793035F72 FOREIGN KEY (alert_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AD6CAEB76B899279 ON intervention_action (patient_id)');
        $this->addSql('CREATE INDEX IDX_AD6CAEB79B125F06 ON intervention_action (emergency_team_id)');
        $this->addSql('CREATE INDEX IDX_AD6CAEB793035F72 ON intervention_action (alert_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention_action DROP FOREIGN KEY FK_AD6CAEB76B899279');
        $this->addSql('ALTER TABLE intervention_action DROP FOREIGN KEY FK_AD6CAEB79B125F06');
        $this->addSql('ALTER TABLE intervention_action DROP FOREIGN KEY FK_AD6CAEB793035F72');
        $this->addSql('DROP INDEX IDX_AD6CAEB76B899279 ON intervention_action');
        $this->addSql('DROP INDEX IDX_AD6CAEB79B125F06 ON intervention_action');
        $this->addSql('DROP INDEX IDX_AD6CAEB793035F72 ON intervention_action');
        $this->addSql('ALTER TABLE intervention_action ADD patient_id_id INT DEFAULT NULL, ADD emergency_team_id_id INT DEFAULT NULL, ADD alertid_id INT DEFAULT NULL, DROP patient_id, DROP emergency_team_id, DROP alert_id');
        $this->addSql('ALTER TABLE intervention_action ADD CONSTRAINT FK_AD6CAEB7ADD13CC8 FOREIGN KEY (alertid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intervention_action ADD CONSTRAINT FK_AD6CAEB7EA724598 FOREIGN KEY (patient_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intervention_action ADD CONSTRAINT FK_AD6CAEB7AC5689CA FOREIGN KEY (emergency_team_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AD6CAEB7EA724598 ON intervention_action (patient_id_id)');
        $this->addSql('CREATE INDEX IDX_AD6CAEB7AC5689CA ON intervention_action (emergency_team_id_id)');
        $this->addSql('CREATE INDEX IDX_AD6CAEB7ADD13CC8 ON intervention_action (alertid_id)');
    }
}
