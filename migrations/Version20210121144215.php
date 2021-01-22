<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121144215 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C479F37AE5');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C49B803341');
        $this->addSql('DROP INDEX IDX_D9BEC0C49B803341 ON commentaires');
        $this->addSql('DROP INDEX IDX_D9BEC0C479F37AE5 ON commentaires');
        $this->addSql('ALTER TABLE commentaires ADD wall_user_id INT NOT NULL, ADD com_user_id INT NOT NULL, DROP id_user_id, DROP id_com_author_id, CHANGE title title VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C47DD59C23 FOREIGN KEY (wall_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C49975955A FOREIGN KEY (com_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C47DD59C23 ON commentaires (wall_user_id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C49975955A ON commentaires (com_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C47DD59C23');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C49975955A');
        $this->addSql('DROP INDEX IDX_D9BEC0C47DD59C23 ON commentaires');
        $this->addSql('DROP INDEX IDX_D9BEC0C49975955A ON commentaires');
        $this->addSql('ALTER TABLE commentaires ADD id_user_id INT NOT NULL, ADD id_com_author_id INT NOT NULL, DROP wall_user_id, DROP com_user_id, CHANGE title title VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C49B803341 FOREIGN KEY (id_com_author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C49B803341 ON commentaires (id_com_author_id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C479F37AE5 ON commentaires (id_user_id)');
    }
}
