<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240704082329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, entry_id INT NOT NULL, answer LONGTEXT NOT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), INDEX IDX_DADD4A25BA364942 (entry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edition (id INT AUTO_INCREMENT NOT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, subscription_from DATETIME DEFAULT NULL, subscription_to DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, visible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE editions_questions (edition_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_85BC43F574281A5E (edition_id), UNIQUE INDEX UNIQ_85BC43F51E27F6BF (question_id), PRIMARY KEY(edition_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edition_group (edition_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_977F4E4D74281A5E (edition_id), INDEX IDX_977F4E4DFE54D947 (group_id), PRIMARY KEY(edition_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edition_user (edition_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3DA43D5774281A5E (edition_id), INDEX IDX_3DA43D57A76ED395 (user_id), PRIMARY KEY(edition_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entry (id INT AUTO_INCREMENT NOT NULL, edition_id INT NOT NULL, user_id INT NOT NULL, jid VARCHAR(20) NOT NULL, pseudo VARCHAR(255) NOT NULL, age INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_2B219D7074281A5E (edition_id), INDEX IDX_2B219D70A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, coords JSON NOT NULL COMMENT \'(DC2Type:json_document)\', country VARCHAR(3) NOT NULL, comment LONGTEXT DEFAULT NULL, rules LONGTEXT DEFAULT NULL, languages JSON NOT NULL, logo VARCHAR(255) DEFAULT NULL, visible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groups_questions (group_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_9C0B896AFE54D947 (group_id), UNIQUE INDEX UNIQ_9C0B896A1E27F6BF (question_id), PRIMARY KEY(group_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_user (group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A4C98D39FE54D947 (group_id), INDEX IDX_A4C98D39A76ED395 (user_id), PRIMARY KEY(group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, required TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, section VARCHAR(255) DEFAULT NULL, `group` INT DEFAULT NULL, INDEX IDX_8D93D649D3CB4A96 (`group`), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25BA364942 FOREIGN KEY (entry_id) REFERENCES entry (id)');
        $this->addSql('ALTER TABLE editions_questions ADD CONSTRAINT FK_85BC43F574281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE editions_questions ADD CONSTRAINT FK_85BC43F51E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE edition_group ADD CONSTRAINT FK_977F4E4D74281A5E FOREIGN KEY (edition_id) REFERENCES edition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE edition_group ADD CONSTRAINT FK_977F4E4DFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE edition_user ADD CONSTRAINT FK_3DA43D5774281A5E FOREIGN KEY (edition_id) REFERENCES edition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE edition_user ADD CONSTRAINT FK_3DA43D57A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D7074281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D70A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE groups_questions ADD CONSTRAINT FK_9C0B896AFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE groups_questions ADD CONSTRAINT FK_9C0B896A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D3CB4A96 FOREIGN KEY (`group`) REFERENCES `group` (`id`) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25BA364942');
        $this->addSql('ALTER TABLE editions_questions DROP FOREIGN KEY FK_85BC43F574281A5E');
        $this->addSql('ALTER TABLE editions_questions DROP FOREIGN KEY FK_85BC43F51E27F6BF');
        $this->addSql('ALTER TABLE edition_group DROP FOREIGN KEY FK_977F4E4D74281A5E');
        $this->addSql('ALTER TABLE edition_group DROP FOREIGN KEY FK_977F4E4DFE54D947');
        $this->addSql('ALTER TABLE edition_user DROP FOREIGN KEY FK_3DA43D5774281A5E');
        $this->addSql('ALTER TABLE edition_user DROP FOREIGN KEY FK_3DA43D57A76ED395');
        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D7074281A5E');
        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D70A76ED395');
        $this->addSql('ALTER TABLE groups_questions DROP FOREIGN KEY FK_9C0B896AFE54D947');
        $this->addSql('ALTER TABLE groups_questions DROP FOREIGN KEY FK_9C0B896A1E27F6BF');
        $this->addSql('ALTER TABLE group_user DROP FOREIGN KEY FK_A4C98D39FE54D947');
        $this->addSql('ALTER TABLE group_user DROP FOREIGN KEY FK_A4C98D39A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D3CB4A96');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE edition');
        $this->addSql('DROP TABLE editions_questions');
        $this->addSql('DROP TABLE edition_group');
        $this->addSql('DROP TABLE edition_user');
        $this->addSql('DROP TABLE entry');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE groups_questions');
        $this->addSql('DROP TABLE group_user');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
