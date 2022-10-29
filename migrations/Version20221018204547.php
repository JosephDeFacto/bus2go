<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018204547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice ADD bus_company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744D750F592 FOREIGN KEY (bus_company_id) REFERENCES bus_company (id)');
        $this->addSql('CREATE INDEX IDX_90651744D750F592 ON invoice (bus_company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744D750F592');
        $this->addSql('DROP INDEX IDX_90651744D750F592 ON invoice');
        $this->addSql('ALTER TABLE invoice DROP bus_company_id');
    }
}
