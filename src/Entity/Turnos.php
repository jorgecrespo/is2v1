<?php

namespace App\Entity;

use App\Repository\TurnosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TurnosRepository::class)]
class Turnos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $paciente_id;

    #[ORM\Column(type: 'integer')]
    private $vacuna_id;

    #[ORM\Column(type: 'integer')]
    private $vacunatorio_id;

    #[ORM\Column(type: 'datetime')]
    private $fecha;

    #[ORM\Column(type: 'string', length: 255)]
    private $estado;

    // #[ORM\OneToMany(mappedBy: 'turno_id', targetEntity: Notificaciones::class)]
    // private $notificaciones;

    public function __construct()
    {
        $this->notificaciones = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPacienteId(): ?int
    {
        return $this->paciente_id;
    }

    public function setPacienteId(int $paciente_id): self
    {
        $this->paciente_id = $paciente_id;

        return $this;
    }

    public function getVacunaId(): ?int
    {
        return $this->vacuna_id;
    }

    public function setVacunaId(int $vacuna_id): self
    {
        $this->vacuna_id = $vacuna_id;

        return $this;
    }

    public function getVacunatorioId(): ?int
    {
        return $this->vacunatorio_id;
    }

    public function setVacunatorioId(int $vacunatorio_id): self
    {
        $this->vacunatorio_id = $vacunatorio_id;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    // /**
    //  * @return Collection<int, Notificaciones>
    //  */
    // public function getNotificaciones(): Collection
    // {
    //     return $this->notificaciones;
    // }

    // public function addNotificacione(Notificaciones $notificacione): self
    // {
    //     if (!$this->notificaciones->contains($notificacione)) {
    //         $this->notificaciones[] = $notificacione;
    //         $notificacione->setTurnoId($this);
    //     }

    //     return $this;
    // }

    // public function removeNotificacione(Notificaciones $notificacione): self
    // {
    //     if ($this->notificaciones->removeElement($notificacione)) {
    //         // set the owning side to null (unless already changed)
    //         if ($notificacione->getTurnoId() === $this) {
    //             $notificacione->setTurnoId(null);
    //         }
    //     }

    //     return $this;
    // }

  


}
