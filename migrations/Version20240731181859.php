<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240731181859 extends AbstractMigration {
    public function getDescription(): string {
        return 'Add comments for aliases';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE aliases ADD comment VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE aliases DROP comment');
    }
}
