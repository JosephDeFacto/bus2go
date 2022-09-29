<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220901211929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bus (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, bus_company_id INT DEFAULT NULL, bus_number INT NOT NULL, fare_number INT NOT NULL, capacity INT NOT NULL, INDEX IDX_2F566B69A76ED395 (user_id), INDEX IDX_2F566B69D750F592 (bus_company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B69A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B69D750F592 FOREIGN KEY (bus_company_id) REFERENCES bus_company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B69A76ED395');
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B69D750F592');
        $this->addSql('DROP TABLE bus');
    }
}
