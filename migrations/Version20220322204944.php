<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220322204944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detailinvoice (id INT AUTO_INCREMENT NOT NULL, totals NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE details');
        $this->addSql('ALTER TABLE detai_invoice DROP total');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE details (id INT AUTO_INCREMENT NOT NULL, invoice_id_id INT DEFAULT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantity INT NOT NULL, amount NUMERIC(12, 2) NOT NULL, vat NUMERIC(12, 2) NOT NULL, line VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_72260B8A429ECEE2 (invoice_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE details ADD CONSTRAINT FK_72260B8A429ECEE2 FOREIGN KEY (invoice_id_id) REFERENCES invoice (id)');
        $this->addSql('DROP TABLE detailinvoice');
        $this->addSql('ALTER TABLE detai_invoice ADD total NUMERIC(10, 0) NOT NULL');
    }
}
