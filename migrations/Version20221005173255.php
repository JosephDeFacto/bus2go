<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221005173255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discount_travel_schedule (id INT AUTO_INCREMENT NOT NULL, travel_schedule_id INT DEFAULT NULL, discount_id INT DEFAULT NULL, INDEX IDX_19ED11F7BB88F45A (travel_schedule_id), INDEX IDX_19ED11F74C7C611F (discount_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE discount_travel_schedule ADD CONSTRAINT FK_19ED11F7BB88F45A FOREIGN KEY (travel_schedule_id) REFERENCES travel_schedule (id)');
        $this->addSql('ALTER TABLE discount_travel_schedule ADD CONSTRAINT FK_19ED11F74C7C611F FOREIGN KEY (discount_id) REFERENCES discount_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discount_travel_schedule DROP FOREIGN KEY FK_19ED11F7BB88F45A');
        $this->addSql('ALTER TABLE discount_travel_schedule DROP FOREIGN KEY FK_19ED11F74C7C611F');
        $this->addSql('DROP TABLE discount_travel_schedule');
    }
}
