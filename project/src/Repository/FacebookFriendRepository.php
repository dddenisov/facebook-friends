<?php

namespace App\Repository;

use App\Entity\FacebookFriend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FacebookFriend>
 *
 * @method FacebookFriend|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacebookFriend|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacebookFriend[]    findAll()
 * @method FacebookFriend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacebookFriendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacebookFriend::class);
    }

    public function add(FacebookFriend $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FacebookFriend $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     * @return int[]
     */
    public function findShortestChainBetweenFriends(int $startUserId, int $endUserId, int $maxHandshakes): array
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());

        $rsm->addScalarResult('friend_id', 'friend_id', 'integer');
        $rsm->addScalarResult('chain', 'chain');
        $rsm->addScalarResult('handshakes', 'handshakes', 'integer');

        $query = $this->getEntityManager()
            ->createNativeQuery('
                CALL WITH_EMULATOR(
                    # recursive table
                    "r_friends",
                    
                    # initial Select
                    "
                        SELECT
                            friend_id,
                            CONCAT(
                                \'|\', user_id, \'|\',
                                \'|\', friend_id, \'|\'
                            ) AS chain,
                            1 AS handshakes
                        FROM facebook_friends
                        WHERE
                            user_id = ' . $startUserId . '
                    ",
                        
                    # recursive Select
                    "
                        SELECT
                            CASE
                                WHEN r_friends.friend_id = facebook_friends.friend_id
                                    THEN facebook_friends.user_id
                                ELSE facebook_friends.friend_id
                            END AS friend_id,
                            CONCAT(
                                r_friends.chain,
                                \'|\',
                                (
                                    CASE
                                        WHEN r_friends.friend_id = facebook_friends.friend_id
                                            THEN facebook_friends.user_id
                                        ELSE facebook_friends.friend_id
                                    END
                                ),
                                \'|\'
                            ) AS chain,
                            (r_friends.handshakes + 1) AS handshakes
                        FROM facebook_friends
                        INNER JOIN r_friends
                            ON (
                                r_friends.friend_id = facebook_friends.friend_id
                                AND r_friends.chain NOT LIKE CONCAT(
                                     \'%|\', facebook_friends.user_id, \'|%\'
                                )
                            ) OR (
                                r_friends.friend_id = facebook_friends.user_id
                                AND r_friends.chain NOT LIKE CONCAT(
                                     \'%|\', facebook_friends.friend_id, \'|%\'
                                )
                            ) 
                        WHERE
                            facebook_friends.user_id IS NOT NULL
                            AND facebook_friends.friend_id IS NOT NULL
                            AND r_friends.friend_id != ' . $endUserId . '
                            AND r_friends.handshakes <= ' . $maxHandshakes . '
                    ",
                    
                    # final Select
                    "
                        SELECT
                            *
                        FROM r_friends
                        WHERE
                            chain LIKE \'%|' . $endUserId . '|%\'
                        ORDER BY
                            CHAR_LENGTH(chain)
                        LIMIT 1
                    ",
                    
                    # max depth
                    ' . $maxHandshakes . ',
                    ""
                )
           ',
           $rsm
        );

        $result = $query->getOneOrNullResult();
        if ($result === null) {
            return [];
        }

        return array_map(static function (string $member) {
            $member = str_replace('|', '', $member);
            return (int) $member;
        }, explode('||', $result['chain']));
    }
}
