<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220228145826 extends AbstractMigration {
    public function getDescription(): string {
        return 'Remove admin column';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE accounts DROP admin');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE accounts ADD admin TINYINT(1) NOT NULL');
    }
}
