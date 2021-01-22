<?php

namespace App\Entity;

use App\Repository\AmisRepository;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;

/**
 * @ORM\Entity(repositoryClass=AmisRepository::class)
 * @ORM\Table(name="`amis`")
 */
class Amis
{
    const STATUS_PENDING = 'STATUS_PENDING';
    const STATUS_BLOCKED = 'STATUS_BLOCKED';

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="mesAmis")
     * @ORM\Id
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="amisAvecMoi")
     * @ORM\Id
     */
    private $friend;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    public function __construct(User $user, User $friend, Bool $blocked = false)
    {
        $this->user = $user;
        $this->friend = $friend;
        if($blocked){
            $this->status = self::STATUS_BLOCKED;
        }
        else{
            $this->status = self::STATUS_PENDING;
        }
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function getFriend(): ?user
    {
        return $this->friend;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(String $status): self
    {
        $this->status = $status;

        return $this;
    }
}
