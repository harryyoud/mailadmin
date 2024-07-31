<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731171500 extends AbstractMigration {
    public function getDescription(): string {
        return 'Switch to freetext source domain on aliases';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE aliases DROP FOREIGN KEY FK_5F12BB3911563BED');
        $this->addSql('DROP INDEX IDX_5F12BB3911563BED ON aliases');
        $this->addSql('ALTER TABLE aliases CHANGE source_domain source_domain VARCHAR(64) DEFAULT NULL');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE aliases CHANGE source_domain source_domain VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE aliases ADD CONSTRAINT FK_5F12BB3911563BED FOREIGN KEY (source_domain) REFERENCES domains (domain)');
        $this->addSql('CREATE INDEX IDX_5F12BB3911563BED ON aliases (source_domain)');
    }
}
