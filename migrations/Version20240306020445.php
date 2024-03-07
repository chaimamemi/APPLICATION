<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306020445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alert ADD bracelet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C1EC886B8 FOREIGN KEY (bracelet_id) REFERENCES bracelet (id)');
        $this->addSql('CREATE INDEX IDX_17FD46C1EC886B8 ON alert (bracelet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C1EC886B8');
        $this->addSql('DROP INDEX IDX_17FD46C1EC886B8 ON alert');
        $this->addSql('ALTER TABLE alert DROP bracelet_id');
    }
}
