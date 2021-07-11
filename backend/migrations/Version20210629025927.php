<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629025927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE roverlay_app (id INT AUTO_INCREMENT NOT NULL, ext_client_id VARCHAR(255) NOT NULL, ext_client_secret VARCHAR(255) NOT NULL, api_client_id VARCHAR(255) NOT NULL, api_client_secret VARCHAR(255) NOT NULL, api_access_token VARCHAR(255) DEFAULT NULL, api_refresh_token VARCHAR(255) DEFAULT NULL, scopes LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', api_expires_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD access_token VARCHAR(255) DEFAULT NULL, ADD refresh_token VARCHAR(255) DEFAULT NULL, ADD expires_at DATETIME DEFAULT NULL, ADD scopes LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE roverlay_app');
        $this->addSql('ALTER TABLE user DROP access_token, DROP refresh_token, DROP expires_at, DROP scopes');
    }
}
