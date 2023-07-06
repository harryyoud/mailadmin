<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230706083811 extends AbstractMigration {
    public function getDescription(): string {
        return 'Split enabled into can_send and can_receive for alias';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE aliases ADD can_send TINYINT(1) NOT NULL, CHANGE enabled can_receive TINYINT(1) NOT NULL');
        $this->addSql('UPDATE aliases SET can_send = can_receive');
    }

    public function down(Schema $schema): void{
        $this->addSql('ALTER TABLE aliases CHANGE can_receive enabled TINYINT(1) NOT NULL, DROP can_send');
    }
}
