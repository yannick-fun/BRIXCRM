<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    /**
     * get subscription en person data by subscription_id form database
     *
     * @param $subscription_id
     * @return array|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function findSubscriptionAndPerson($subscription_id): ?array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM `subscription`
                JOIN `person` ON `subscription`.`person_id_id` = `person`.`id`
                WHERE `subscription`.`id` = :subscription_id'
        ;

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute(['subscription_id' => $subscription_id]);
            return $stmt->fetchAssociative();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * get all invoices from database by subscription_id form database
     *
     * @param $subscription_id
     * @return array|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function findInvoices($subscription_id): ?array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM `invoice` 
                WHERE `subscription_id_id` = :subscription_id'
        ;

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute(['subscription_id' => $subscription_id]);
            return $stmt->fetchAllAssociative();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * get all not cancelled orders by subscription_id form database
     *
     * @param $subscription_id
     * @return array|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function findNotCancelledOrders($subscription_id): ?array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM `order` 
                JOIN `order_type` ON `order_type`.`id` = `order`.`type_id` 
                JOIN `order_status` ON `order_status`.`id` = `order`.`status_id`
                WHERE `subscription_id_id` = :subscription_id AND `status_option` != "cancelled"'
        ;

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute(['subscription_id' => $subscription_id]);
            return $stmt->fetchAllAssociative();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * get all subscriptions form database
     *
     * @return array|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function findAllSubscriptionAndPerson(): ?array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT `subscription`.`id`, `person_id_id`,`phone_number`,`first_name`, `last_name` FROM `subscription`
                JOIN `person` ON `subscription`.`person_id_id` = `person`.`id`'
        ;

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([]);
            return $stmt->fetchAllAssociative();
        } catch (Exception $e) {
            return null;
        }
    }
}