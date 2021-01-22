<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="wallUser", orphanRemoval=true)
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
        if($this->avatar){
            $filesystem = new Filesystem();

            try{
                $filesystem->remove('../public/' . $this->avatar);
            }catch (FileException $e){
                throw $e;
            }
        }

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

    /**
     * @return Commentaires
     */
    public function getCommentaireAsId(int $id): ?Commentaires
    {
        foreach ($this->commentaires as $commentaire){
            if($commentaire->getId() == $id)
            {
                return $commentaire;
            }
        }
        return null;
    }

    public function removeCommentaire(int $comId): self
    {
        foreach ($this->commentaires as $commentaire){
            if($commentaire->getId() == $comId){
                $commentaire->removeImage();
                $this->commentaires->removeElement($commentaire);
                break;
            }
        }

        return $this;
    }

    public function getToutLesAmis(): Collection
    {
        $amis = new ArrayCollection();

        foreach ($this->mesAmis as $ami){
            $amis[] = $ami->getFriend();
        }
        foreach ($this->amisAvecMoi as $ami){
            $amis[] = $ami->getUser();
        }

        $orderBy = (Criteria::create())->orderBy([
            'id' => Criteria::ASC,
        ]);

        return $amis->matching($orderBy);
    }

    /**
     * @return Collection|User[]
     */
    public function getAmis(String $status): Collection
    {
        $amis = new ArrayCollection();

        foreach ($this->mesAmis as $ami){
            if($ami->getStatus() == $status){
                $amis[] = $ami->getFriend();
            }
        }
        foreach ($this->amisAvecMoi as $ami){
            if($ami->getStatus() == $status){
                $amis[] = $ami->getUser();
            }
        }

        $orderBy = (Criteria::create())->orderBy([
            'username' => Criteria::ASC,
        ]);

        return $amis->matching($orderBy);
    }

    /**
     * @return Amis
     */
    public function getAmiById(int $id): ?Amis
    {
        foreach ($this->mesAmis as $ami){
            if($ami->getFriend()->getId() == $id){
                return $ami;
            }
        }
        foreach ($this->amisAvecMoi as $ami){
            if($ami->getUser()->getId() == $id){
                return $ami;
            }
        }
        return null;
    }

    /**
    * @return bool
    */
    public function estEnRelationAvec(String $status, int $id): bool{
        foreach ($this->mesAmis as $ami){
            if($ami->getFriend()->GetId() == $id && $ami->getStatus() == $status){
                return true;
            }
        }
        foreach ($this->amisAvecMoi as $ami){
            if($ami->getUser()->GetId() == $id && $ami->getStatus() == $status){
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function AucuneRelationAvec(int $id): bool{
        foreach ($this->mesAmis as $ami){
            if($ami->getFriend()->GetId() == $id){
                return false;
            }
        }
        foreach ($this->amisAvecMoi as $ami){
            if($ami->getUser()->GetId() == $id){
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function aDemanderEnAmi(int $id): bool{
        foreach ($this->mesAmis as $ami){
            if($ami->getFriend()->getId() == $id){
                return true;
            }
        }
        return false;
    }

    /**
     * @return int
     */
    public function getInviteNumber(): int{
        $nb = 0;

        foreach ($this->amisAvecMoi as $ami){
            if($ami->getStatus() == 'STATUS_PENDING'){
                $nb += 1;
            }
        }

        return $nb;
    }

    public function removeAmi(int $id): self
    {
        $ami = $this->getAmiById($id);

        $this->mesAmis->removeElement($ami);
        $this->amisAvecMoi->removeElement($ami);

        return $this;
    }

    public function getAmisNumber(): int{
        $n1 = $this->mesAmis->count();
        $n2 = $this->amisAvecMoi->count();
        return $n1 + $n2;
    }
}
