<?php

namespace App\Entity;

use App\Repository\UsuariosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuariosRepository::class)]
class Usuarios implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $mail;

    #[ORM\Column(type: 'string', length: 255)]
    private $pass;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $es_admin;

    #[ORM\Column(type: 'string', length: 255)]
    private $nombre;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $fecha_baja;

    #[ORM\ManyToOne(targetEntity: Vacunatorios::class)]
    private $vacunatorio_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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
        return base64_decode($this->pass);
    }

    public function getPassword(): ?string
    {
        return base64_decode($this->pass);
    }

    public function setPass(string $pass): self
    {
        $this->pass = base64_encode($pass);

        return $this;
    }

    public function getEsAdmin(): ?bool
    {
        return $this->es_admin;
    }

    public function setEsAdmin(?bool $es_admin): self
    {
        $this->es_admin = $es_admin;

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

    public function getFechaBaja(): ?\DateTimeInterface
    {
        return $this->fecha_baja;
    }

    public function getfecha_baja(): ?String
    {
        if (isset($this->fecha_baja))
        return date_format($this->fecha_baja, "d-m-Y");
        else
        return '';
    }

    public function setFechaBaja(?\DateTimeInterface $fecha_baja): self
    {
        $this->fecha_baja = $fecha_baja;

        return $this;
    }

    public function getVacunatorioId(): ?vacunatorios
    {
        return $this->vacunatorio_id;
    }

    public function setVacunatorioId(?vacunatorios $vacunatorio_id): self
    {
        $this->vacunatorio_id = $vacunatorio_id;

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
