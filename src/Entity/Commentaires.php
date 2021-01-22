<?php

namespace App\Entity;

use App\Repository\CommentairesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @ORM\Entity(repositoryClass=CommentairesRepository::class)
 */
class Commentaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wallUser;

    /**
     * @ORM\ManyToOne(targetEntity=user::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $comUser;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_crea;

    public function __construct(User $wallUser, User $comUser, String $title, String $content){
        $this->wallUser = $wallUser;
        $this->comUser = $comUser;
        $this->title = $title;
        $this->content = $content;
        $this->date_crea = \DateTime::createFromFormat('d-m-Y H:i:s', date('d-m-Y H:i:s'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWallUser(): ?user
    {
        return $this->wallUser;
    }

    public function getComUser(): ?user
    {
        return $this->comUser;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getDateCrea(): ?\DateTimeInterface
    {
        return $this->date_crea;
    }

    public function setImage(String $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function removeImage(): self{
        if($this->image){
            $filesystem = new Filesystem();

            try{
                $filesystem->remove('../public/' . $this->image);
            }catch (FileException $e){
                throw $e;
            }
        }

        $this->image = null;

        return $this;
    }
}
