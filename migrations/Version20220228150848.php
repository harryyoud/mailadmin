<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220228150848 extends AbstractMigration {
    public function getDescription(): string {
        return 'Fixup dummy field';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE anyone_shares CHANGE dummy dummy VARCHAR(1) DEFAULT \'1\'');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE anyone_shares CHANGE dummy dummy CHAR(1) CHARACTER SET utf8mb4 DEFAULT \'1\' COLLATE `utf8mb4_unicode_ci`');
    }
}
