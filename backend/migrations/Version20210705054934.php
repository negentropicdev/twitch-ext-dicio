<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705054934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE control (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, mapped_controls LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', disabled TINYINT(1) NOT NULL, style VARCHAR(255) NOT NULL, style_params LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE control_group (id INT AUTO_INCREMENT NOT NULL, channel_id INT NOT NULL, dynamic_user_id INT DEFAULT NULL, group_queue_id INT DEFAULT NULL, active_user_id INT DEFAULT NULL, cloned_from_id INT DEFAULT NULL, queue_gated TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, is_dynamic TINYINT(1) NOT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_D639ED9872F5A1AA (channel_id), INDEX IDX_D639ED9879123D8E (dynamic_user_id), INDEX IDX_D639ED984E4B17D1 (group_queue_id), INDEX IDX_D639ED9824226F8B (active_user_id), INDEX IDX_D639ED98B2CF0654 (cloned_from_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE control_value (id INT AUTO_INCREMENT NOT NULL, control_group_id INT NOT NULL, cloned_from_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, cur_value DOUBLE PRECISION NOT NULL, read_only TINYINT(1) NOT NULL, default_value DOUBLE PRECISION NOT NULL, last_update DATETIME DEFAULT NULL, updated_by_type VARCHAR(255) DEFAULT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_A68EF16921942907 (control_group_id), INDEX IDX_A68EF169B2CF0654 (cloned_from_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_queue (id INT AUTO_INCREMENT NOT NULL, channel_id INT NOT NULL, queued_users LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', time_limit INT NOT NULL, start_time DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2FD1540472F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE control_group ADD CONSTRAINT FK_D639ED9872F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
        $this->addSql('ALTER TABLE control_group ADD CONSTRAINT FK_D639ED9879123D8E FOREIGN KEY (dynamic_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE control_group ADD CONSTRAINT FK_D639ED984E4B17D1 FOREIGN KEY (group_queue_id) REFERENCES group_queue (id)');
        $this->addSql('ALTER TABLE control_group ADD CONSTRAINT FK_D639ED9824226F8B FOREIGN KEY (active_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE control_group ADD CONSTRAINT FK_D639ED98B2CF0654 FOREIGN KEY (cloned_from_id) REFERENCES control_group (id)');
        $this->addSql('ALTER TABLE control_value ADD CONSTRAINT FK_A68EF16921942907 FOREIGN KEY (control_group_id) REFERENCES control_group (id)');
        $this->addSql('ALTER TABLE control_value ADD CONSTRAINT FK_A68EF169B2CF0654 FOREIGN KEY (cloned_from_id) REFERENCES control_value (id)');
        $this->addSql('ALTER TABLE group_queue ADD CONSTRAINT FK_2FD1540472F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
        $this->addSql('DROP TABLE control_set');
        $this->addSql('ALTER TABLE client CHANGE channel_id channel_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE control_group DROP FOREIGN KEY FK_D639ED98B2CF0654');
        $this->addSql('ALTER TABLE control_value DROP FOREIGN KEY FK_A68EF16921942907');
        $this->addSql('ALTER TABLE control_value DROP FOREIGN KEY FK_A68EF169B2CF0654');
        $this->addSql('ALTER TABLE control_group DROP FOREIGN KEY FK_D639ED984E4B17D1');
        $this->addSql('CREATE TABLE control_set (id INT AUTO_INCREMENT NOT NULL, channel_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, enabled TINYINT(1) NOT NULL, visible TINYINT(1) NOT NULL, INDEX IDX_6A4ADE8F72F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE control_set ADD CONSTRAINT FK_6A4ADE8F72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
        $this->addSql('DROP TABLE control');
        $this->addSql('DROP TABLE control_group');
        $this->addSql('DROP TABLE control_value');
        $this->addSql('DROP TABLE group_queue');
        $this->addSql('ALTER TABLE client CHANGE channel_id channel_id INT DEFAULT NULL');
    }
}
