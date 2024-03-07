<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306021222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bracelet DROP FOREIGN KEY FK_93F6777D93035F72');
        $this->addSql('DROP INDEX IDX_93F6777D93035F72 ON bracelet');
        $this->addSql('ALTER TABLE bracelet DROP alert_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bracelet ADD alert_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bracelet ADD CONSTRAINT FK_93F6777D93035F72 FOREIGN KEY (alert_id) REFERENCES alert (id)');
        $this->addSql('CREATE INDEX IDX_93F6777D93035F72 ON bracelet (alert_id)');
    }
}
