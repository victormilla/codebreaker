<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181019134317 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attempted_guess (id INT AUTO_INCREMENT NOT NULL, codebreaker_id INT DEFAULT NULL, guess VARCHAR(255) NOT NULL COMMENT \'(DC2Type:codebreaker_code)\', exact INT NOT NULL, partial INT NOT NULL, INDEX IDX_3BF4E446D7091452 (codebreaker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE codebreakers (id INT AUTO_INCREMENT NOT NULL, secret VARCHAR(255) NOT NULL COMMENT \'(DC2Type:codebreaker_code)\', attempts INT NOT NULL, found TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attempted_guess ADD CONSTRAINT FK_3BF4E446D7091452 FOREIGN KEY (codebreaker_id) REFERENCES codebreakers (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attempted_guess DROP FOREIGN KEY FK_3BF4E446D7091452');
        $this->addSql('DROP TABLE attempted_guess');
        $this->addSql('DROP TABLE codebreakers');
    }
}