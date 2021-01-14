<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210114215455 extends AbstractMigration {
    public function getDescription(): string {
        return 'Add TLS policies';
    }

    public function up(Schema $schema): void {
        $this->addSql('CREATE TABLE tlspolicies (id INT AUTO_INCREMENT NOT NULL, domain VARCHAR(255) NOT NULL, policy VARCHAR(255) NOT NULL, params VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A220EBD4A7A91E0B (domain), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void {
        $this->addSql('DROP TABLE tlspolicies');
    }
}
