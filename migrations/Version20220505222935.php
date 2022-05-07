<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505222935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aplicaciones (id INT AUTO_INCREMENT NOT NULL, turno_id_id INT NOT NULL, efectos VARCHAR(255) DEFAULT NULL, lote VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_94765120176868FF (turno_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pacientes (id INT AUTO_INCREMENT NOT NULL, mail VARCHAR(255) NOT NULL, pass VARCHAR(255) NOT NULL, token VARCHAR(5) NOT NULL, nombre VARCHAR(255) NOT NULL, apellido VARCHAR(255) NOT NULL, de_riesgo TINYINT(1) NOT NULL, fecha_nac DATETIME NOT NULL, vacuna_gripe_fecha DATETIME DEFAULT NULL, vacuna_covid1_fecha DATETIME DEFAULT NULL, vacuna_covid2_fecha DATETIME DEFAULT NULL, vacuna_hepatitis_fecha DATETIME DEFAULT NULL, notificacion_pendiente TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE turnos (id INT AUTO_INCREMENT NOT NULL, paciente_id_id INT NOT NULL, vacuna_id_id INT NOT NULL, fecha DATETIME NOT NULL, estado VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B85558188AA1655E (paciente_id_id), UNIQUE INDEX UNIQ_B8555818D02B4DA (vacuna_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuarios (id INT AUTO_INCREMENT NOT NULL, vacunatorio_id_id INT DEFAULT NULL, mail VARCHAR(255) NOT NULL, pass VARCHAR(255) NOT NULL, es_admin TINYINT(1) DEFAULT NULL, nombre VARCHAR(255) NOT NULL, fecha_baja DATETIME DEFAULT NULL, INDEX IDX_EF687F2A18D51C0 (vacunatorio_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacunas (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacunatorios (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, direccion VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aplicaciones ADD CONSTRAINT FK_94765120176868FF FOREIGN KEY (turno_id_id) REFERENCES turnos (id)');
        $this->addSql('ALTER TABLE turnos ADD CONSTRAINT FK_B85558188AA1655E FOREIGN KEY (paciente_id_id) REFERENCES pacientes (id)');
        $this->addSql('ALTER TABLE turnos ADD CONSTRAINT FK_B8555818D02B4DA FOREIGN KEY (vacuna_id_id) REFERENCES vacunas (id)');
        $this->addSql('ALTER TABLE usuarios ADD CONSTRAINT FK_EF687F2A18D51C0 FOREIGN KEY (vacunatorio_id_id) REFERENCES vacunatorios (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE turnos DROP FOREIGN KEY FK_B85558188AA1655E');
        $this->addSql('ALTER TABLE aplicaciones DROP FOREIGN KEY FK_94765120176868FF');
        $this->addSql('ALTER TABLE turnos DROP FOREIGN KEY FK_B8555818D02B4DA');
        $this->addSql('ALTER TABLE usuarios DROP FOREIGN KEY FK_EF687F2A18D51C0');
        $this->addSql('DROP TABLE aplicaciones');
        $this->addSql('DROP TABLE pacientes');
        $this->addSql('DROP TABLE turnos');
        $this->addSql('DROP TABLE usuarios');
        $this->addSql('DROP TABLE vacunas');
        $this->addSql('DROP TABLE vacunatorios');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
