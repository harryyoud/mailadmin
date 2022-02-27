<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220224155157 extends AbstractMigration {
    public function getDescription(): string {
        return '';
    }

    public function up(Schema $schema): void {
        $this->addSql('CREATE TABLE passwords (id INT AUTO_INCREMENT NOT NULL, mailbox INT NOT NULL, app_name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_ED822B16A69FE20B (mailbox), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE passwords ADD CONSTRAINT FK_ED822B16A69FE20B FOREIGN KEY (mailbox) REFERENCES accounts (id)');
    }

    public function down(Schema $schema): void {
        $this->addSql('DROP TABLE passwords');
    }
}
