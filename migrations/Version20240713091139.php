<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240713091139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE discount (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, discount_type VARCHAR(20) NOT NULL, discount_type_id INTEGER NOT NULL, percentage INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, sku VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, price INTEGER NOT NULL, CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
        $this->addSql('CREATE INDEX idx_name ON category(name)');
        $this->addSql('CREATE INDEX idx_type_id ON discount(discount_type, discount_type_id)');
        $this->addSql('CREATE INDEX idx_category ON product(category_id)');
        $this->addSql('CREATE INDEX idx_sku ON product(sku)');


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE product');
    }
}
