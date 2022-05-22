<?php

namespace App\Service;

use App\Repository\FacebookFriendRepository;
use Doctrine\ORM\NonUniqueResultException;

class FacebookFriendService
{
    public function __construct(private FacebookFriendRepository $facebookFriendRepository)
    {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findShortestChainBetweenFriends(
        int $startUserId,
        int $endUserId,
        int $maxHandshakes = 4
    ): array {
        return $this->facebookFriendRepository->findShortestChainBetweenFriends(
            $startUserId,
            $endUserId,
            $maxHandshakes
        );
    }
}