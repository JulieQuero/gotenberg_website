<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240612080844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pdf_user DROP FOREIGN KEY FK_7EB29B07511FC912');
        $this->addSql('ALTER TABLE pdf_user DROP FOREIGN KEY FK_7EB29B07A76ED395');
        $this->addSql('DROP TABLE pdf_user');
        $this->addSql('ALTER TABLE pdf ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE pdf ADD CONSTRAINT FK_EF0DB8CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EF0DB8CA76ED395 ON pdf (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pdf_user (pdf_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7EB29B07511FC912 (pdf_id), INDEX IDX_7EB29B07A76ED395 (user_id), PRIMARY KEY(pdf_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE pdf_user ADD CONSTRAINT FK_7EB29B07511FC912 FOREIGN KEY (pdf_id) REFERENCES pdf (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pdf_user ADD CONSTRAINT FK_7EB29B07A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pdf DROP FOREIGN KEY FK_EF0DB8CA76ED395');
        $this->addSql('DROP INDEX IDX_EF0DB8CA76ED395 ON pdf');
        $this->addSql('ALTER TABLE pdf DROP user_id');
    }
}
