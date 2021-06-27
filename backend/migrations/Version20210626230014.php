<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210626230014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE channel (id INT AUTO_INCREMENT NOT NULL, channel_id VARCHAR(255) NOT NULL, channel_name VARCHAR(255) NOT NULL, approved TINYINT(1) NOT NULL, live TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, channel_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, secret VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, last_active DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_C744045572F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE control_set (id INT AUTO_INCREMENT NOT NULL, channel_id INT NOT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, visible TINYINT(1) NOT NULL, INDEX IDX_6A4ADE8F72F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, opaque_id VARCHAR(255) NOT NULL, pubsub_listen LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', pubsub_send LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', user_id VARCHAR(255) DEFAULT NULL, broadcaster_type VARCHAR(20) DEFAULT NULL, display_name VARCHAR(255) DEFAULT NULL, login VARCHAR(255) DEFAULT NULL, profile_image_url VARCHAR(255) DEFAULT NULL, type VARCHAR(20) DEFAULT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C744045572F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
        $this->addSql('ALTER TABLE control_set ADD CONSTRAINT FK_6A4ADE8F72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C744045572F5A1AA');
        $this->addSql('ALTER TABLE control_set DROP FOREIGN KEY FK_6A4ADE8F72F5A1AA');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE control_set');
        $this->addSql('DROP TABLE user');
    }
}
