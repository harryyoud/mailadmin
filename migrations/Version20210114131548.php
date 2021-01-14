<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210114131548 extends AbstractMigration {
    public function getDescription(): string {
        return 'Create initial table schema';
    }

    public function up(Schema $schema): void {
        $this->addSql('CREATE TABLE accounts (id INT AUTO_INCREMENT NOT NULL, domain VARCHAR(255) DEFAULT NULL, username VARCHAR(64) NOT NULL, password VARCHAR(255) NOT NULL, quota INT NOT NULL, enabled TINYINT(1) NOT NULL, sendonly TINYINT(1) NOT NULL, INDEX IDX_CAC89EACA7A91E0B (domain), UNIQUE INDEX username (username, domain), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aliases (id INT AUTO_INCREMENT NOT NULL, source_domain VARCHAR(255) DEFAULT NULL, source_username VARCHAR(64) NOT NULL, destination_username VARCHAR(64) NOT NULL, destination_domain VARCHAR(255) NOT NULL, INDEX IDX_5F12BB3911563BED (source_domain), UNIQUE INDEX username (source_username, source_domain, destination_username, destination_domain), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domains (id INT AUTO_INCREMENT NOT NULL, domain VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8C7BBF9DA7A91E0B (domain), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EACA7A91E0B FOREIGN KEY (domain) REFERENCES domains (domain)');
        $this->addSql('ALTER TABLE aliases ADD CONSTRAINT FK_5F12BB3911563BED FOREIGN KEY (source_domain) REFERENCES domains (domain)');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE accounts DROP FOREIGN KEY FK_CAC89EACA7A91E0B');
        $this->addSql('ALTER TABLE aliases DROP FOREIGN KEY FK_5F12BB3911563BED');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE aliases');
        $this->addSql('DROP TABLE domains');
    }
}
