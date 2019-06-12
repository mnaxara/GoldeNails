<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190612083525 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_prestation DROP FOREIGN KEY FK_C2AFD0869E45C554');
        $this->addSql('ALTER TABLE rendezvous_prestation DROP FOREIGN KEY FK_656BD8519E45C554');
        $this->addSql('ALTER TABLE service_prestation DROP FOREIGN KEY FK_C799B6199E45C554');
        $this->addSql('ALTER TABLE product_prestation DROP FOREIGN KEY FK_C2AFD0864584665A');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_prestation');
        $this->addSql('DROP TABLE rendezvous_prestation');
        $this->addSql('DROP TABLE service_prestation');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, duration TIME NOT NULL, comments VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_51C88FADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci, description LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, quantity INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_prestation (product_id INT NOT NULL, prestation_id INT NOT NULL, INDEX IDX_C2AFD0869E45C554 (prestation_id), INDEX IDX_C2AFD0864584665A (product_id), PRIMARY KEY(product_id, prestation_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rendezvous_prestation (rendezvous_id INT NOT NULL, prestation_id INT NOT NULL, INDEX IDX_656BD8519E45C554 (prestation_id), INDEX IDX_656BD8513345E0A3 (rendezvous_id), PRIMARY KEY(rendezvous_id, prestation_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE service_prestation (service_id INT NOT NULL, prestation_id INT NOT NULL, INDEX IDX_C799B6199E45C554 (prestation_id), INDEX IDX_C799B619ED5CA9E6 (service_id), PRIMARY KEY(service_id, prestation_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_prestation ADD CONSTRAINT FK_C2AFD0864584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_prestation ADD CONSTRAINT FK_C2AFD0869E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rendezvous_prestation ADD CONSTRAINT FK_656BD8513345E0A3 FOREIGN KEY (rendezvous_id) REFERENCES rendezvous (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rendezvous_prestation ADD CONSTRAINT FK_656BD8519E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_prestation ADD CONSTRAINT FK_C799B6199E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_prestation ADD CONSTRAINT FK_C799B619ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
    }
}
