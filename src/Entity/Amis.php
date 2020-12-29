<?php

namespace App\Entity;

use App\Repository\AmisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AmisRepository::class)
 */
class Amis
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="amis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_demandeur;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="amis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_receveur;

    /**
     * @ORM\Column(type="smallint")
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDemandeur(): ?user
    {
        return $this->id_demandeur;
    }

    public function setIdDemandeur(?user $id_demandeur): self
    {
        $this->id_demandeur = $id_demandeur;

        return $this;
    }

    public function getIdReceveur(): ?user
    {
        return $this->id_receveur;
    }

    public function setIdReceveur(?user $id_receveur): self
    {
        $this->id_receveur = $id_receveur;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
