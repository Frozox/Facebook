<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201229105716 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE amis (id INT AUTO_INCREMENT NOT NULL, id_demandeur_id INT NOT NULL, id_receveur_id INT NOT NULL, etat SMALLINT NOT NULL, INDEX IDX_9FE2E761B6679B2B (id_demandeur_id), INDEX IDX_9FE2E761A1752BD1 (id_receveur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_com_author_id INT NOT NULL, title VARCHAR(30) NOT NULL, content VARCHAR(2048) NOT NULL, image VARCHAR(255) DEFAULT NULL, date_crea DATE NOT NULL, INDEX IDX_D9BEC0C479F37AE5 (id_user_id), INDEX IDX_D9BEC0C49B803341 (id_com_author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE amis ADD CONSTRAINT FK_9FE2E761B6679B2B FOREIGN KEY (id_demandeur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE amis ADD CONSTRAINT FK_9FE2E761A1752BD1 FOREIGN KEY (id_receveur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C479F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C49B803341 FOREIGN KEY (id_com_author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD avatar VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE amis');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('ALTER TABLE `user` DROP avatar');
    }
}
