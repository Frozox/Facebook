<?php

namespace App\Entity;

use App\Repository\AmisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AmisRepository::class)
 */
class Amis
{
    const STATUS_PENDING = 'STATUS_PENDING';
    const STATUS_FRIEND = 'STATUS_FRIEND';
    const STATUS_BLOCKED = 'STATUS_BLOCKED';
    const DEFAULT_STATUS = [self::STATUS_PENDING];

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


    public function __construct()
    {
        $this->status = self::DEFAULT_STATUS;
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

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function addFriend(User $user, User $friend): self
    {

        $userID = $user->getId();
        $friendID = $friend->getId();


        switch ($userID)
        {
            case $userID > $friendID:
                $this->setUser($friend);
                $this->setFriend($user);
                break;
            case $userID < $friendID:
                $this->setUser($user);
                $this->setFriend($friend);
                break;
            case $userID ===  $friendID:
                break;
        }

        return $this;
    }
}
