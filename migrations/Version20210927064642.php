<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927064642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE IF EXISTS transactions RENAME TO transaction;');
        $this->addSql('ALTER TABLE IF EXISTS creditnails RENAME TO creditnail;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE IF EXISTS transaction RENAME TO transactions;');
        $this->addSql('ALTER TABLE IF EXISTS creditnail RENAME TO creditnails;');

    }
}
