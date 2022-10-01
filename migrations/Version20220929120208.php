<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220929120208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_ticket ADD travel_schedule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart_ticket ADD CONSTRAINT FK_9924CFFCBB88F45A FOREIGN KEY (travel_schedule_id) REFERENCES travel_schedule (id)');
        $this->addSql('CREATE INDEX IDX_9924CFFCBB88F45A ON cart_ticket (travel_schedule_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_ticket DROP FOREIGN KEY FK_9924CFFCBB88F45A');
        $this->addSql('DROP INDEX IDX_9924CFFCBB88F45A ON cart_ticket');
        $this->addSql('ALTER TABLE cart_ticket DROP travel_schedule_id');
    }
}
