<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250920121614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE driver_review (id INT AUTO_INCREMENT NOT NULL, trip_id INT NOT NULL, rater_id INT DEFAULT NULL, rating VARCHAR(20) NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3C1C5F7EA5BC2E0E (trip_id), INDEX IDX_3C1C5F7E3FC1CD0A (rater_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE driver_review ADD CONSTRAINT FK_3C1C5F7EA5BC2E0E FOREIGN KEY (trip_id) REFERENCES carpooling (id)');
        $this->addSql('ALTER TABLE driver_review ADD CONSTRAINT FK_3C1C5F7E3FC1CD0A FOREIGN KEY (rater_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver_review DROP FOREIGN KEY FK_3C1C5F7EA5BC2E0E');
        $this->addSql('ALTER TABLE driver_review DROP FOREIGN KEY FK_3C1C5F7E3FC1CD0A');
        $this->addSql('DROP TABLE driver_review');
    }
}
