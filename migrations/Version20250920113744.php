<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250920113744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carpooling (id INT AUTO_INCREMENT NOT NULL, driver_id INT NOT NULL, vehicle_id INT NOT NULL, origin_city VARCHAR(50) NOT NULL, destination_city VARCHAR(50) NOT NULL, departure_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', arrival_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', seats_total SMALLINT NOT NULL, seats_available SMALLINT NOT NULL, price_credits SMALLINT NOT NULL, status VARCHAR(20) NOT NULL, eco_tag TINYINT(1) NOT NULL, duration_minutes INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6CC153F1C3423909 (driver_id), INDEX IDX_6CC153F1545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carpooling ADD CONSTRAINT FK_6CC153F1C3423909 FOREIGN KEY (driver_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE carpooling ADD CONSTRAINT FK_6CC153F1545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE user DROP photo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carpooling DROP FOREIGN KEY FK_6CC153F1C3423909');
        $this->addSql('ALTER TABLE carpooling DROP FOREIGN KEY FK_6CC153F1545317D1');
        $this->addSql('DROP TABLE carpooling');
        $this->addSql('ALTER TABLE `user` ADD photo VARCHAR(255) DEFAULT NULL');
    }
}
