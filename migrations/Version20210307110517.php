<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210307110517 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, subscription_id_id INT NOT NULL, paid TINYINT(1) NOT NULL, amount DOUBLE PRECISION NOT NULL, invoice_date DATETIME NOT NULL, INDEX IDX_90651744857C9F24 (subscription_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, subscription_id_id INT NOT NULL, status_id INT DEFAULT NULL, type_id INT DEFAULT NULL, order_date DATETIME NOT NULL, INDEX IDX_F5299398857C9F24 (subscription_id_id), INDEX IDX_F52993986BF700BD (status_id), INDEX IDX_F5299398C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_status (id INT AUTO_INCREMENT NOT NULL, status_option VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_type (id INT AUTO_INCREMENT NOT NULL, type_option VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, actions_blocked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, person_id_id INT NOT NULL, phone_number VARCHAR(15) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_A3C664D3D3728193 (person_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744857C9F24 FOREIGN KEY (subscription_id_id) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398857C9F24 FOREIGN KEY (subscription_id_id) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993986BF700BD FOREIGN KEY (status_id) REFERENCES order_status (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398C54C8C93 FOREIGN KEY (type_id) REFERENCES order_type (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3D3728193 FOREIGN KEY (person_id_id) REFERENCES person (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993986BF700BD');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398C54C8C93');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3D3728193');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744857C9F24');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398857C9F24');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_status');
        $this->addSql('DROP TABLE order_type');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE subscription');
    }
}
