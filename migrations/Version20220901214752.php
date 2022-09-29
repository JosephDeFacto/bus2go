<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220901214752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE travel_schedule (id INT AUTO_INCREMENT NOT NULL, bus_id INT DEFAULT NULL, bus_driver_id INT DEFAULT NULL, depart_from VARCHAR(50) NOT NULL, travel_to VARCHAR(50) NOT NULL, departing_on DATE NOT NULL, returning_on DATE NOT NULL, departure_time TIME NOT NULL, time_of_arrival TIME NOT NULL, estimated_arrival_time TIME NOT NULL, fee DOUBLE PRECISION NOT NULL, INDEX IDX_304972BE2546731D (bus_id), INDEX IDX_304972BED7A61BE3 (bus_driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE travel_schedule ADD CONSTRAINT FK_304972BE2546731D FOREIGN KEY (bus_id) REFERENCES bus (id)');
        $this->addSql('ALTER TABLE travel_schedule ADD CONSTRAINT FK_304972BED7A61BE3 FOREIGN KEY (bus_driver_id) REFERENCES driver (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE travel_schedule DROP FOREIGN KEY FK_304972BE2546731D');
        $this->addSql('ALTER TABLE travel_schedule DROP FOREIGN KEY FK_304972BED7A61BE3');
        $this->addSql('DROP TABLE travel_schedule');
    }
}
