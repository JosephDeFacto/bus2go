<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220926171934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_ticket (id INT AUTO_INCREMENT NOT NULL, travel_schedule_id INT DEFAULT NULL, cart_id INT DEFAULT NULL, INDEX IDX_9924CFFCBB88F45A (travel_schedule_id), INDEX IDX_9924CFFC1AD5CDBF (cart_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_ticket ADD CONSTRAINT FK_9924CFFCBB88F45A FOREIGN KEY (travel_schedule_id) REFERENCES travel_schedule (id)');
        $this->addSql('ALTER TABLE cart_ticket ADD CONSTRAINT FK_9924CFFC1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE user DROP is_verified');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_ticket DROP FOREIGN KEY FK_9924CFFCBB88F45A');
        $this->addSql('ALTER TABLE cart_ticket DROP FOREIGN KEY FK_9924CFFC1AD5CDBF');
        $this->addSql('DROP TABLE cart_ticket');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL');
    }
}
