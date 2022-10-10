<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221009183836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398AAFE604D');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398AAFE604D FOREIGN KEY (cart_ticket_id) REFERENCES cart_ticket (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398AAFE604D');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398AAFE604D FOREIGN KEY (cart_ticket_id) REFERENCES cart_ticket (id) ON DELETE SET NULL');
    }
}
