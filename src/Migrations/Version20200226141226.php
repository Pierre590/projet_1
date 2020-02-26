<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200226141226 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ride (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, company_id INT DEFAULT NULL, departure_id INT DEFAULT NULL, arrival_id INT DEFAULT NULL, schedule DATETIME NOT NULL, space_available INT NOT NULL, observations VARCHAR(255) DEFAULT NULL, INDEX IDX_9B3D7CD0A76ED395 (user_id), INDEX IDX_9B3D7CD0979B1AD6 (company_id), INDEX IDX_9B3D7CD07704ED06 (departure_id), INDEX IDX_9B3D7CD062789708 (arrival_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zip (id INT AUTO_INCREMENT NOT NULL, code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, adress_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone INT NOT NULL, INDEX IDX_4FBF094F8486F9AC (adress_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resa (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, ride_id INT DEFAULT NULL, INDEX IDX_90C9C53BA76ED395 (user_id), INDEX IDX_90C9C53B302A8A70 (ride_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, adress_id INT DEFAULT NULL, company_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone INT NOT NULL, google_id INT NOT NULL, UNIQUE INDEX UNIQ_1483A5E98486F9AC (adress_id), INDEX IDX_1483A5E9979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, zip_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2D5B02347D662686 (zip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, adress VARCHAR(255) NOT NULL, zip INT NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD07704ED06 FOREIGN KEY (departure_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD062789708 FOREIGN KEY (arrival_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F8486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE resa ADD CONSTRAINT FK_90C9C53BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE resa ADD CONSTRAINT FK_90C9C53B302A8A70 FOREIGN KEY (ride_id) REFERENCES ride (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E98486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B02347D662686 FOREIGN KEY (zip_id) REFERENCES zip (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resa DROP FOREIGN KEY FK_90C9C53B302A8A70');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B02347D662686');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0979B1AD6');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9979B1AD6');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0A76ED395');
        $this->addSql('ALTER TABLE resa DROP FOREIGN KEY FK_90C9C53BA76ED395');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD07704ED06');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD062789708');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F8486F9AC');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E98486F9AC');
        $this->addSql('DROP TABLE ride');
        $this->addSql('DROP TABLE zip');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE resa');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE adress');
    }
}
