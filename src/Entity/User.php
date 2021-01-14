<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="id_user", orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Amis::class, mappedBy="user", orphanRemoval=true)
     */
    private $mesAmis;

    /**
     * @ORM\OneToMany(targetEntity=Amis::class, mappedBy="friend", orphanRemoval=true)
     */
    private $amisAvecMoi;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->mesAmis = new ArrayCollection();
        $this->amisAvecMoi = new ArrayCollection();
    }

    /**
     * @see UserInterface
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @see UserInterface
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function removeAvatar(): self
    {
        $this->avatar = null;

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setIdUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdUser() === $this) {
                $commentaire->setIdUser(null);
            }
        }

        return $this;
    }

    ///**
    // * @return Collection|User[]
    // */
    /*public function getPendingAmis(): Collection{
        return new ArrayCollection();
    }*/

    /**
     * @return Collection|User[]
     */
    public function getAmis(): Collection
    {
        $amis = new ArrayCollection();

        foreach ($this->mesAmis as $ami){
            $amis[] = $ami->getFriend();
        }
        foreach ($this->amisAvecMoi as $ami){
            $amis[] = $ami->getUser();
        }

        $orderBy = (Criteria::create())->orderBy([
            'username' => Criteria::ASC,
        ]);

        return $amis->matching($orderBy);
    }

    ///**
    // * @return Collection|User[]
    // */
    /*public function getPendingAmis(): Collection{
    }*/

    /*public function addAmi(Amis $ami): self
    {
        if (!$this->amis->contains($ami)) {
            $this->amis[] = $ami;
            $ami->setIdDemandeur($this);
        }

        return $this;
    }

    public function removeAmi(Amis $ami): self
    {
        if ($this->amis->removeElement($ami)) {
            // set the owning side to null (unless already changed)
            if ($ami->getIdDemandeur() === $this) {
                $ami->setIdDemandeur(null);
            }
        }

        return $this;
    }*/
}
