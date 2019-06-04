<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190201194453 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rendezvous (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_C09A9BA8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendezvous_prestation (rendezvous_id INT NOT NULL, prestation_id INT NOT NULL, INDEX IDX_656BD8513345E0A3 (rendezvous_id), INDEX IDX_656BD8519E45C554 (prestation_id), PRIMARY KEY(rendezvous_id, prestation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendezvous_service (rendezvous_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_545A78AE3345E0A3 (rendezvous_id), INDEX IDX_545A78AEED5CA9E6 (service_id), PRIMARY KEY(rendezvous_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendezvous_prestation ADD CONSTRAINT FK_656BD8513345E0A3 FOREIGN KEY (rendezvous_id) REFERENCES rendezvous (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rendezvous_prestation ADD CONSTRAINT FK_656BD8519E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rendezvous_service ADD CONSTRAINT FK_545A78AE3345E0A3 FOREIGN KEY (rendezvous_id) REFERENCES rendezvous (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rendezvous_service ADD CONSTRAINT FK_545A78AEED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rendezvous_prestation DROP FOREIGN KEY FK_656BD8513345E0A3');
        $this->addSql('ALTER TABLE rendezvous_service DROP FOREIGN KEY FK_545A78AE3345E0A3');
        $this->addSql('DROP TABLE rendezvous');
        $this->addSql('DROP TABLE rendezvous_prestation');
        $this->addSql('DROP TABLE rendezvous_service');
    }
}
