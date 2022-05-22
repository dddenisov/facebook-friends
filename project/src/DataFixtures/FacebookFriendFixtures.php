<?php

namespace App\DataFixtures;

use App\Entity\FacebookFriend;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FacebookFriendFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $friends = [
            ['user_id' => 1, 'friend_id' => 2],
            ['user_id' => 3, 'friend_id' => 2],
            ['user_id' => 8, 'friend_id' => 2],
            ['user_id' => 3, 'friend_id' => 4],
            ['user_id' => 4, 'friend_id' => 5],
            ['user_id' => 4, 'friend_id' => 6],
            ['user_id' => 6, 'friend_id' => 7],
        ];

        foreach ($friends as $friendData) {
            $friend = new FacebookFriend();
            $friend->setUserId($friendData['user_id']);
            $friend->setFriendId($friendData['friend_id']);

            $manager->persist($friend);
        }

        $manager->flush();
    }
}
