<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702101217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE editions_questions DROP FOREIGN KEY FK_85BC43F51E27F6BF');
        $this->addSql('ALTER TABLE editions_questions ADD CONSTRAINT FK_85BC43F51E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entry ADD edition_id INT NOT NULL');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D7074281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('CREATE INDEX IDX_2B219D7074281A5E ON entry (edition_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE editions_questions DROP FOREIGN KEY FK_85BC43F51E27F6BF');
        $this->addSql('ALTER TABLE editions_questions ADD CONSTRAINT FK_85BC43F51E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D7074281A5E');
        $this->addSql('DROP INDEX IDX_2B219D7074281A5E ON entry');
        $this->addSql('ALTER TABLE entry DROP edition_id');
    }
}
