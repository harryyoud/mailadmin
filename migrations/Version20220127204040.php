<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220127204040 extends AbstractMigration {
    public function getDescription(): string {
        return 'Allow null in source_domain in aliases to account for wildcards (e.g. postmaster@)';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE aliases CHANGE source_domain source_domain VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE aliases CHANGE source_domain source_domain VARCHAR(255) NOT NULL');
    }
}
