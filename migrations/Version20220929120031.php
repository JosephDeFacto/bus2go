<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220929120031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_ticket ADD cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart_ticket ADD CONSTRAINT FK_9924CFFC1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_9924CFFC1AD5CDBF ON cart_ticket (cart_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_ticket DROP FOREIGN KEY FK_9924CFFC1AD5CDBF');
        $this->addSql('DROP INDEX IDX_9924CFFC1AD5CDBF ON cart_ticket');
        $this->addSql('ALTER TABLE cart_ticket DROP cart_id');
    }
}
