<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120092513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_petshop (product_id INT NOT NULL, petshop_id INT NOT NULL, INDEX IDX_79985FBD4584665A (product_id), INDEX IDX_79985FBDFB57ADCC (petshop_id), PRIMARY KEY(product_id, petshop_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_petshop ADD CONSTRAINT FK_79985FBD4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_petshop ADD CONSTRAINT FK_79985FBDFB57ADCC FOREIGN KEY (petshop_id) REFERENCES petshop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animal ADD petshop_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FFB57ADCC FOREIGN KEY (petshop_id) REFERENCES petshop (id)');
        $this->addSql('CREATE INDEX IDX_6AAB231FFB57ADCC ON animal (petshop_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_petshop DROP FOREIGN KEY FK_79985FBD4584665A');
        $this->addSql('ALTER TABLE product_petshop DROP FOREIGN KEY FK_79985FBDFB57ADCC');
        $this->addSql('DROP TABLE product_petshop');
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FFB57ADCC');
        $this->addSql('DROP INDEX IDX_6AAB231FFB57ADCC ON animal');
        $this->addSql('ALTER TABLE animal DROP petshop_id');
    }
}
