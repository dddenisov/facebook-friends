<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220522084641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE facebook_friends
            (
                id INT AUTO_INCREMENT NOT NULL,
                user_id BIGINT DEFAULT NULL,
                friend_id BIGINT DEFAULT NULL,
                friend_name VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY(id),
                KEY `user_id` (`user_id`),
                KEY `friend_id` (`friend_id`),
                KEY `user_id_2` (`user_id`,`friend_id`)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE facebook_friends');
    }
}
