<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627231735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notificaciones DROP FOREIGN KEY FK_6FFCB21176868FF');
        $this->addSql('DROP INDEX IDX_6FFCB21176868FF ON notificaciones');
        $this->addSql('ALTER TABLE turnos ADD fecha_baja DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notificaciones ADD CONSTRAINT FK_6FFCB21176868FF FOREIGN KEY (turno_id) REFERENCES turnos (id)');
        $this->addSql('CREATE INDEX IDX_6FFCB21176868FF ON notificaciones (turno_id)');
        $this->addSql('ALTER TABLE turnos DROP fecha_baja');
    }
}
