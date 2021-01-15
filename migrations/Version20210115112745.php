<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210115112745 extends AbstractMigration {
    public function getDescription(): string {
        return 'Add admin';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE accounts ADD admin TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE accounts DROP admin');
    }
}
