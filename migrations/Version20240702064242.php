<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702064242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groups_questions DROP FOREIGN KEY FK_9C0B896A1E27F6BF');
        $this->addSql('ALTER TABLE groups_questions ADD CONSTRAINT FK_9C0B896A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groups_questions DROP FOREIGN KEY FK_9C0B896A1E27F6BF');
        $this->addSql('ALTER TABLE groups_questions ADD CONSTRAINT FK_9C0B896A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
    }
}
