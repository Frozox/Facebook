<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210113084650 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE amis MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE amis DROP FOREIGN KEY FK_9FE2E761A1752BD1');
        $this->addSql('ALTER TABLE amis DROP FOREIGN KEY FK_9FE2E761B6679B2B');
        $this->addSql('DROP INDEX IDX_9FE2E761A1752BD1 ON amis');
        $this->addSql('DROP INDEX IDX_9FE2E761B6679B2B ON amis');
        $this->addSql('ALTER TABLE amis DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE amis ADD user_id INT NOT NULL, ADD friend_id INT NOT NULL, ADD status LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', DROP id, DROP id_demandeur_id, DROP id_receveur_id, DROP etat');
        $this->addSql('ALTER TABLE amis ADD CONSTRAINT FK_9FE2E761A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE amis ADD CONSTRAINT FK_9FE2E7616A5458E8 FOREIGN KEY (friend_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_9FE2E761A76ED395 ON amis (user_id)');
        $this->addSql('CREATE INDEX IDX_9FE2E7616A5458E8 ON amis (friend_id)');
        $this->addSql('ALTER TABLE amis ADD PRIMARY KEY (user_id, friend_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE amis DROP FOREIGN KEY FK_9FE2E761A76ED395');
        $this->addSql('ALTER TABLE amis DROP FOREIGN KEY FK_9FE2E7616A5458E8');
        $this->addSql('DROP INDEX IDX_9FE2E761A76ED395 ON amis');
        $this->addSql('DROP INDEX IDX_9FE2E7616A5458E8 ON amis');
        $this->addSql('ALTER TABLE amis ADD id INT AUTO_INCREMENT NOT NULL, ADD id_demandeur_id INT NOT NULL, ADD id_receveur_id INT NOT NULL, ADD etat SMALLINT NOT NULL, DROP user_id, DROP friend_id, DROP status, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE amis ADD CONSTRAINT FK_9FE2E761A1752BD1 FOREIGN KEY (id_receveur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE amis ADD CONSTRAINT FK_9FE2E761B6679B2B FOREIGN KEY (id_demandeur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9FE2E761A1752BD1 ON amis (id_receveur_id)');
        $this->addSql('CREATE INDEX IDX_9FE2E761B6679B2B ON amis (id_demandeur_id)');
    }
}
