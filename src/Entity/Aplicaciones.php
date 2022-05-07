<?php

namespace App\Entity;

use App\Repository\AplicacionesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AplicacionesRepository::class)]
class Aplicaciones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(targetEntity: turnos::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $turno_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $efectos;

    #[ORM\Column(type: 'string', length: 255)]
    private $lote;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTurnoId(): ?turnos
    {
        return $this->turno_id;
    }

    public function setTurnoId(turnos $turno_id): self
    {
        $this->turno_id = $turno_id;

        return $this;
    }

    public function getEfectos(): ?string
    {
        return $this->efectos;
    }

    public function setEfectos(?string $efectos): self
    {
        $this->efectos = $efectos;

        return $this;
    }

    public function getLote(): ?string
    {
        return $this->lote;
    }

    public function setLote(string $lote): self
    {
        $this->lote = $lote;

        return $this;
    }
}
