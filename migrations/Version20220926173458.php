<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220926173458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE travel_schedule DROP FOREIGN KEY FK_304972BE1AD5CDBF');
        $this->addSql('DROP INDEX IDX_304972BE1AD5CDBF ON travel_schedule');
        $this->addSql('ALTER TABLE travel_schedule DROP cart_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE travel_schedule ADD cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE travel_schedule ADD CONSTRAINT FK_304972BE1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_304972BE1AD5CDBF ON travel_schedule (cart_id)');
    }
}
