<?php

namespace App\Entity;

use App\Repository\NotificacionesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificacionesRepository::class)]
class Notificaciones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: turnos::class, inversedBy: 'notificaciones')]
    #[ORM\JoinColumn(nullable: false)]
    private $turno_id;

    #[ORM\Column(type: 'integer')]
    private $antelacion;

    #[ORM\Column(type: 'boolean')]
    private $leida;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTurnoId(): ?turnos
    {
        return $this->turno_id;
    }

    public function setTurnoId(?turnos $turno_id): self
    {
        $this->turno_id = $turno_id;

        return $this;
    }

    public function getAntelacion(): ?int
    {
        return $this->antelacion;
    }

    public function setAntelacion(int $antelacion): self
    {
        $this->antelacion = $antelacion;

        return $this;
    }

    public function getLeida(): ?bool
    {
        return $this->leida;
    }

    public function setLeida(bool $leida): self
    {
        $this->leida = $leida;

        return $this;
    }
}
