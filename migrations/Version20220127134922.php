<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220127134922 extends AbstractMigration {
    public function getDescription(): string {
        return 'Add mailbox share tables';
    }

    public function up(Schema $schema): void {
        $this->addSql('CREATE TABLE anyone_shares (from_user VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, dummy CHAR(1) CHARACTER SET utf8mb4 DEFAULT \'1\' COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(from_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'User from_user shares folders to anyone.\' ');
        $this->addSql('CREATE TABLE user_shares (from_user VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, to_user VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, dummy CHAR(1) CHARACTER SET utf8mb4 DEFAULT \'1\' COLLATE `utf8mb4_unicode_ci`, INDEX to_user (to_user), PRIMARY KEY(from_user, to_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'User from_user shares folders to user to_user.\' ');
    }

    public function down(Schema $schema): void {
        $this->addSql('DROP TABLE anyone_shares');
        $this->addSql('DROP TABLE user_shares');
    }
}
