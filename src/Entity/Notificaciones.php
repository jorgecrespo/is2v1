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

    #[ORM\Column(type: 'integer')]
    private $turno_id;

    #[ORM\Column(type: 'integer')]
    private $antelacion;

    #[ORM\Column(type: 'boolean')]
    private $leida;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTurnoId(): ?int
    {
        return $this->turno_id;
    }

    public function setTurnoId(int $turno_id): self
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
