<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005182055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_car (event_id INT NOT NULL, car_id INT NOT NULL, INDEX IDX_66F2234E71F7E88B (event_id), INDEX IDX_66F2234EC3C6F69F (car_id), PRIMARY KEY(event_id, car_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_car ADD CONSTRAINT FK_66F2234E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_car ADD CONSTRAINT FK_66F2234EC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7C3C6F69F');
        $this->addSql('DROP INDEX IDX_3BAE0AA7C3C6F69F ON event');
        $this->addSql('ALTER TABLE event DROP car_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_car DROP FOREIGN KEY FK_66F2234E71F7E88B');
        $this->addSql('ALTER TABLE event_car DROP FOREIGN KEY FK_66F2234EC3C6F69F');
        $this->addSql('DROP TABLE event_car');
        $this->addSql('ALTER TABLE event ADD car_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7C3C6F69F ON event (car_id)');
    }
}
