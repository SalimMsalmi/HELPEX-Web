<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */

/**
     * @param string|array $roles
     *
     * @return User[]
     */

   public function findPros($roles): array
  {
        return $this->createQueryBuilder('u')
           ->andWhere('u.roles LIKE :roles')
           ->setParameter('roles','%"' . $roles[0] . '"%')
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByEmail($email): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email  = :val')
            ->setParameter('val', $email)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByEmail1($email)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email  = :val')
            ->setParameter('val', $email)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
           ->getOneOrNullResult()
            ;
    }

   public function findByFiliere($id) 
   {
    $qb= $this->createQueryBuilder('f')
    ->join('f.filiere','c')
    ->addSelect('c')
    ->where('c.id=:id')
    ->setParameter('id',$id);
return $qb->getQuery()
    ->getResult();
  }
}
