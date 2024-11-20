<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120090734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE petshop ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE petshop ADD CONSTRAINT FK_A841440DF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A841440DF5B7AF75 ON petshop (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE petshop DROP FOREIGN KEY FK_A841440DF5B7AF75');
        $this->addSql('DROP INDEX UNIQ_A841440DF5B7AF75 ON petshop');
        $this->addSql('ALTER TABLE petshop DROP address_id');
    }
}
