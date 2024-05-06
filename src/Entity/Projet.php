<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $duree = null;

    #[ORM\Column(length: 255)]
    private ?string $pre_requis = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?bool $selected;

    #[ORM\ManyToOne(inversedBy: 'projets')]
    private ?Enseignant $enseignant = null;

    /**
     * @var Collection<int, Etudiant>
     */
    #[ORM\OneToMany(targetEntity: Etudiant::class, mappedBy: 'projet')]
    private Collection $etudiants;
    public function __construct()
    {
        // Par défaut, lors de la création d'un nouveau projet, 'selected' est défini sur false
        $this->selected = false;
        $this->etudiants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDuree(): ?float
    {
        return $this->duree;
    }

    public function setDuree(float $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getPreRequis(): ?string
    {
        return $this->pre_requis;
    }

    public function setPreRequis(string $pre_requis): static
    {
        $this->pre_requis = $pre_requis;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function isSelected(): ?bool
    {
        return $this->selected;
    }

    public function setSelected(bool $selected): static
    {
        $this->selected = $selected;

        return $this;
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): static
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): static
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants->add($etudiant);
            $etudiant->setProjet($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): static
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getProjet() === $this) {
                $etudiant->setProjet(null);
            }
        }

        return $this;
    }
}
