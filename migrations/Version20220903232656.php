<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220903232656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver ADD bus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD92546731D FOREIGN KEY (bus_id) REFERENCES bus (id)');
        $this->addSql('CREATE INDEX IDX_11667CD92546731D ON driver (bus_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD92546731D');
        $this->addSql('DROP INDEX IDX_11667CD92546731D ON driver');
        $this->addSql('ALTER TABLE driver DROP bus_id');
    }
}
