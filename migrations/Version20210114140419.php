<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210114140419 extends AbstractMigration {
    public function getDescription(): string {
        return 'Use domain as primary key';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE domains MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE domains DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE domains DROP id');
        $this->addSql('ALTER TABLE domains ADD PRIMARY KEY (domain)');
        $this->addSql('DROP INDEX UNIQ_8C7BBF9DA7A91E0B ON domains');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE domains ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C7BBF9DA7A91E0B ON domains (domain)');
    }
}
