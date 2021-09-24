<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210924123156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tarif_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE task_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE account (id UUID NOT NULL, owner_id INT DEFAULT NULL, operator_id INT DEFAULT NULL, game_id UUID DEFAULT NULL, external_id VARCHAR(255) DEFAULT NULL, status INT NOT NULL, price INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7D3656A47E3C61F9 ON account (owner_id)');
        $this->addSql('CREATE INDEX IDX_7D3656A4584598A3 ON account (operator_id)');
        $this->addSql('CREATE INDEX IDX_7D3656A4E48FD905 ON account (game_id)');
        $this->addSql('COMMENT ON COLUMN account.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN account.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE creditnails (id UUID NOT NULL, account_id UUID NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_test BOOLEAN NOT NULL, is_active BOOLEAN NOT NULL, valid_till DATE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7E1F95F39B6B5FBA ON creditnails (account_id)');
        $this->addSql('COMMENT ON COLUMN creditnails.valid_till IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN creditnails.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN creditnails.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE finance_account (id UUID NOT NULL, owner_id INT DEFAULT NULL, amount INT NOT NULL, active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_90A4BA007E3C61F9 ON finance_account (owner_id)');
        $this->addSql('COMMENT ON COLUMN finance_account.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN finance_account.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE game (id UUID NOT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, price INT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN game.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN game.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tarif (id INT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, number_of_accounts INT NOT NULL, price_account INT NOT NULL, price_change_account INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN tarif.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tarif.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE task (id UUID NOT NULL, head_id INT DEFAULT NULL, operator_id INT DEFAULT NULL, type_id INT NOT NULL, account_id UUID DEFAULT NULL, dead_line DATE DEFAULT NULL, status INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB25F41A619E ON task (head_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25584598A3 ON task (operator_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25C54C8C93 ON task (type_id)');
        $this->addSql('CREATE INDEX IDX_527EDB259B6B5FBA ON task (account_id)');
        $this->addSql('COMMENT ON COLUMN task.dead_line IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE task_type (id INT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, award INT NOT NULL, overdue_penalty INT NOT NULL, reject_penalty INT NOT NULL, default_duration INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE transactions (id UUID NOT NULL, finance_id UUID NOT NULL, account_id UUID DEFAULT NULL, type INT NOT NULL, amount INT NOT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAA81A4C5E87A6C2 ON transactions (finance_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C9B6B5FBA ON transactions (account_id)');
        $this->addSql('COMMENT ON COLUMN transactions.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN transactions.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A47E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4584598A3 FOREIGN KEY (operator_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE creditnails ADD CONSTRAINT FK_7E1F95F39B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE finance_account ADD CONSTRAINT FK_90A4BA007E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25F41A619E FOREIGN KEY (head_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25584598A3 FOREIGN KEY (operator_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25C54C8C93 FOREIGN KEY (type_id) REFERENCES task_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB259B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C5E87A6C2 FOREIGN KEY (finance_id) REFERENCES finance_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE creditnails DROP CONSTRAINT FK_7E1F95F39B6B5FBA');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB259B6B5FBA');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4C9B6B5FBA');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4C5E87A6C2');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT FK_7D3656A4E48FD905');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25C54C8C93');
        $this->addSql('DROP SEQUENCE tarif_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE task_type_id_seq CASCADE');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE creditnails');
        $this->addSql('DROP TABLE finance_account');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE tarif');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_type');
        $this->addSql('DROP TABLE transactions');
    }
}
