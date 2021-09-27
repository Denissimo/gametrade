<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927131907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ALTER dead_line TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE task ALTER dead_line DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN task.dead_line IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ALTER dead_line TYPE DATE');
        $this->addSql('ALTER TABLE task ALTER dead_line DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN task.dead_line IS \'(DC2Type:date_immutable)\'');
    }
}
