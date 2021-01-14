<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114132049 extends AbstractMigration {
    public function getDescription(): string {
        return 'Don\'t allow null domain';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE accounts CHANGE domain domain VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE aliases CHANGE source_domain source_domain VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE accounts CHANGE domain domain VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE aliases CHANGE source_domain source_domain VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
