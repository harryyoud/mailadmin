<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220227193554 extends AbstractMigration {
    public function getDescription(): string {
        return 'Make app-specific passwords unique';
    }

    public function up(Schema $schema): void {
        $this->addSql('CREATE UNIQUE INDEX app_password ON passwords (app_name, mailbox)');
    }

    public function down(Schema $schema): void{
        $this->addSql('DROP INDEX app_password ON passwords');
    }
}
