<?php

namespace App\Entity;

use App\Repository\FacebookFriendRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'facebook_friends', indexes: [
    new ORM\Index(fields: ['user_id'], name: 'user_id'),
    new ORM\Index(fields: ['friend_id'], name: 'friend_id'),
    new ORM\Index(fields: ['user_id', 'friend_id'], name: 'user_id_2'),
])]
#[ORM\Entity(repositoryClass: FacebookFriendRepository::class)]
class FacebookFriend
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, length: 11)]
    private int $id;

    #[ORM\Column(name: 'user_id', type: Types::BIGINT, length: 20, nullable: true)]
    private int $userId;

    #[ORM\Column(name: 'friend_id', type: Types::BIGINT, nullable: true)]
    private int $friendId;

    #[ORM\Column(name: 'friend_name', type: Types::STRING, length: 255, nullable: true)]
    private string $friendName;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getFriendId(): ?int
    {
        return $this->friendId;
    }

    public function setFriendId(?int $friendId): self
    {
        $this->friendId = $friendId;

        return $this;
    }

    public function getFriendName(): ?string
    {
        return $this->friendName;
    }

    public function setFriendName(?string $friendName): self
    {
        $this->friendName = $friendName;

        return $this;
    }
}
