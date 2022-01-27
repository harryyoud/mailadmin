<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220127210335 extends AbstractMigration {
    public function getDescription(): string {
        return 'Allow null in source_username in aliases to account for catchalls';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE aliases CHANGE source_username source_username VARCHAR(64) DEFAULT NULL');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE aliases CHANGE source_username source_username VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
