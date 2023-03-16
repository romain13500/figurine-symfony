<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316171520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_figurines (purchase_id INT NOT NULL, figurines_id INT NOT NULL, INDEX IDX_C09519AA558FBEB9 (purchase_id), INDEX IDX_C09519AA6DD12435 (figurines_id), PRIMARY KEY(purchase_id, figurines_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase_figurines ADD CONSTRAINT FK_C09519AA558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purchase_figurines ADD CONSTRAINT FK_C09519AA6DD12435 FOREIGN KEY (figurines_id) REFERENCES figurines (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purchase CHANGE purchased_at purchased_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_figurines DROP FOREIGN KEY FK_C09519AA558FBEB9');
        $this->addSql('ALTER TABLE purchase_figurines DROP FOREIGN KEY FK_C09519AA6DD12435');
        $this->addSql('DROP TABLE purchase_figurines');
        $this->addSql('ALTER TABLE purchase CHANGE purchased_at purchased_at DATETIME NOT NULL');
    }
}
