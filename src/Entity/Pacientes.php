<?php

namespace App\Entity;

use App\Repository\PacientesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: PacientesRepository::class)]

class Pacientes implements UserInterface,  PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $mail;

    #[ORM\Column(type: 'string', length: 255)]
    private $pass;

    #[ORM\Column(type: 'string', length: 5)]
    private $token;

    #[ORM\Column(type: 'string', length: 255)]
    private $nombre;

    #[ORM\Column(type: 'string', length: 255)]
    private $apellido;

    #[ORM\Column(type: 'boolean')]
    private $de_riesgo;

    #[ORM\Column(type: 'datetime')]
    private $fecha_nac;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $vacuna_gripe_fecha;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $vacuna_covid1_fecha;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $vacuna_covid2_fecha;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $vacuna_hepatitis_fecha;

    #[ORM\Column(type: 'boolean')]
    private $notificacion_pendiente;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function getPassword(): string
    {
        return $this->pass;
    }

    public function setPass(string $pass): self
    {
        $this->pass = $pass;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getDeRiesgo(): ?bool
    {
        return $this->de_riesgo;
    }

    public function setDeRiesgo(bool $de_riesgo): self
    {
        $this->de_riesgo = $de_riesgo;

        return $this;
    }

    public function getFechaNac(): ?\DateTimeInterface
    {
        return $this->fecha_nac;
    }

    public function setFechaNac(\DateTimeInterface $fecha_nac): self
    {
        $this->fecha_nac = $fecha_nac;

        return $this;
    }

    public function getVacunaGripeFecha(): ?\DateTimeInterface
    {
        return $this->vacuna_gripe_fecha;
    }

    public function setVacunaGripeFecha(?\DateTimeInterface $vacuna_gripe_fecha): self
    {
        $this->vacuna_gripe_fecha = $vacuna_gripe_fecha;

        return $this;
    }

    public function getVacunaCovid1Fecha(): ?\DateTimeInterface
    {
        return $this->vacuna_covid1_fecha;
    }

    public function setVacunaCovid1Fecha(?\DateTimeInterface $vacuna_covid1_fecha): self
    {
        $this->vacuna_covid1_fecha = $vacuna_covid1_fecha;

        return $this;
    }

    public function getVacunaCovid2Fecha(): ?\DateTimeInterface
    {
        return $this->vacuna_covid2_fecha;
    }

    public function setVacunaCovid2Fecha(?\DateTimeInterface $vacuna_covid2_fecha): self
    {
        $this->vacuna_covid2_fecha = $vacuna_covid2_fecha;

        return $this;
    }

    public function getVacunaHepatitisFecha(): ?\DateTimeInterface
    {
        return $this->vacuna_hepatitis_fecha;
    }

    public function setVacunaHepatitisFecha(?\DateTimeInterface $vacuna_hepatitis_fecha): self
    {
        $this->vacuna_hepatitis_fecha = $vacuna_hepatitis_fecha;

        return $this;
    }

    public function getNotificacionPendiente(): ?bool
    {
        return $this->notificacion_pendiente;
    }

    public function setNotificacionPendiente(bool $notificacion_pendiente): self
    {
        $this->notificacion_pendiente = $notificacion_pendiente;

        return $this;
    }

    
    public function getRoles(): array
    {
        return ['ROLE_USER']; 
    }

    
    public function getUserIdentifier(): string
    {
        return $this->mail; 
    }

    public function eraseCredentials()
    {
        $this->setMail('');
    }

}
