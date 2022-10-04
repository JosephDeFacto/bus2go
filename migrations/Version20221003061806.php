<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221003061806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_ticket ADD passenger_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart_ticket ADD CONSTRAINT FK_9924CFFC2DB0C693 FOREIGN KEY (passenger_type_id) REFERENCES passenger_type (id)');
        $this->addSql('CREATE INDEX IDX_9924CFFC2DB0C693 ON cart_ticket (passenger_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_ticket DROP FOREIGN KEY FK_9924CFFC2DB0C693');
        $this->addSql('DROP INDEX IDX_9924CFFC2DB0C693 ON cart_ticket');
        $this->addSql('ALTER TABLE cart_ticket DROP passenger_type_id');
    }
}
