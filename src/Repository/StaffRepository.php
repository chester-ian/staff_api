<?php

namespace App\Repository;

use App\Entity\Staff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @extends ServiceEntityRepository<Staff>
 *
 * @method Staff|null find($id, $lockMode = null, $lockVersion = null)
 * @method Staff|null findOneBy(array $criteria, array $orderBy = null)
 * @method Staff[]    findAll()
 * @method Staff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffRepository extends ServiceEntityRepository
{
    
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Staff::class);
        $this->em = $em;
    }

    public function getStaff($id): array
    {
        if ($id == 0) 
        {
            // Your SQL query
            $sql = "SELECT id,email,firstname,lastname,squad,status,notes FROM staff LIMIT 1000";
            $connection = $this->em->getConnection();
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            $result = $stmt->execute()->fetchAll(\PDO::FETCH_ASSOC);
        }
        elseif ($id > 0) 
        {
            $sql = "SELECT id,email,firstname,lastname,squad,status,notes FROM staff WHERE id = :id ";
            $connection = $this->em->getConnection();
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->execute()->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $result;
    }

    public function updateStaff($id, $content): string
    {
        try{
            $myData = $this->em->getRepository(Staff::class)->find($id);
            if ($myData){
                $myData->setEmail($content->email);
                $myData->setPassword($content->password);
                $myData->setFirstname($content->firstname);
                $myData->setLastname($content->lastname);
                $myData->setSquad($content->squad);
                $myData->setStatus($content->status);
                $myData->setNotes($content->notes);
                $this->em->persist($myData);
                $this->em->flush();
            }
        }
        catch(\Exception $e){
            return 0;
        }
        return 1;
    }

    public function addStaff($content): string
    {
        try{
            // Create a new staff
            $newStaff = new Staff();
            $newStaff->setEmail($content->email);
            $newStaff->setPassword($content->password);
            $newStaff->setFirstname($content->firstname);
            $newStaff->setLastname($content->lastname);
            $newStaff->setSquad($content->squad);
            $newStaff->setStatus($content->status);
            $newStaff->setNotes($content->notes);
            $this->em->persist($newStaff);
            $this->em->flush();
            return $newStaff->getId();
        }
        catch(\Exception $e){
            return 0;
        }
    }

    public function deleteStaff($id): string
    {
        try{
            $myData = $this->em->getRepository(Staff::class)->find($id);
            if ($myData){
                $this->em->remove($myData);
                $this->em->flush();
            }
        }
        catch(\Exception $e){
            return 0;
        }
        return 1;
    }

    public function isValidEmail($email): int
    {
            $sql = "SELECT email FROM staff WHERE email = :email";
            $connection = $this->em->getConnection();
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->execute()->fetchAll(\PDO::FETCH_ASSOC);
            if ($result){
                return 0;
            }
            return 1;
    }
    public function isValidId($id): int
    {
            $sql = "SELECT id FROM staff WHERE id = :id";
            $connection = $this->em->getConnection();
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->execute()->fetchAll(\PDO::FETCH_ASSOC);
            if ($result){
                return 1;
            }
            return 0;
    }
    
}
