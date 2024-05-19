<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514154955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE mark_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mark (id INT NOT NULL, author_id INT DEFAULT NULL, product_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6674F271F675F31B ON mark (author_id)');
        $this->addSql('CREATE INDEX IDX_6674F2714584665A ON mark (product_id)');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F271F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F2714584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE mark_id_seq CASCADE');
        $this->addSql('ALTER TABLE mark DROP CONSTRAINT FK_6674F271F675F31B');
        $this->addSql('ALTER TABLE mark DROP CONSTRAINT FK_6674F2714584665A');
        $this->addSql('DROP TABLE mark');
    }
}
