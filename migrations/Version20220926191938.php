<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220926191938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart ADD travel_schedule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7BB88F45A FOREIGN KEY (travel_schedule_id) REFERENCES travel_schedule (id)');
        $this->addSql('CREATE INDEX IDX_BA388B7BB88F45A ON cart (travel_schedule_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7BB88F45A');
        $this->addSql('DROP INDEX IDX_BA388B7BB88F45A ON cart');
        $this->addSql('ALTER TABLE cart DROP travel_schedule_id');
    }
}
