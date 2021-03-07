<?php

namespace App\Controller;

use App\Repository\SubscriptionRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SimController extends AbstractController
{

    private $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     * @throws Exception
     */
    public function index(): Response
    {
        $data = $this->subscriptionRepository->findAllSubscriptionAndPerson();

        return $this->render('index.html.twig', [
            'subscriptions' => $data,
        ]);
    }

    /**
     * Checks if subscription is allowed to activate sim
     *
     * @Route("/sim/{subscription_id}", name="sim", methods={"GET"})
     * @param int $subscription_id
     * @return Response
     * @throws Exception
     */
    public function simActivateAllowed($subscription_id): Response
    {
        //Determined if activation is granted
        $allowed = false;

        //Get data form database by using "$subscription_id"
        $subscription = $this->subscriptionRepository->findSubscriptionAndPerson($subscription_id);
        $invoices = $this->subscriptionRepository->findInvoices($subscription_id);
        $orders = $this->subscriptionRepository->findNotCancelledOrders($subscription_id);

        //Functions that determines if subscription meets requirements
        $blockedPerson = $this->checkBlockedPerson($subscription['actions_blocked']);
        $isActivated = $this->checkActivationCustomer($orders);
        $activeSubscription = $this->checkActiveSubscription($subscription['active']);
        $paidInvoices = $this->checkPaidInvoice($invoices);
        $pendingSimActivation =$this->checkPendingSimActivation($orders);
        $simActivationCount = $this->checkSimActivations($orders);
        $hasNewSimCard = $this->hasNewSimCard($orders);

        //Sim activation is allowed if all functions return "true"
        if ($blockedPerson && $isActivated && $activeSubscription && $paidInvoices && $pendingSimActivation && $simActivationCount && $hasNewSimCard){
            $allowed = true;
        }

        return $this->render('sim.html.twig', [
            'subscription' => $subscription,
            'allowed' => $allowed
        ]);
    }

    /**
     * If user is not blocked, return true
     *
     * @param $personAction
     * @return bool
     */
    private function checkBlockedPerson($personAction): bool
    {
        return $personAction === '0';
    }

    /**
     * if successful connection is found, return true
     *
     * @param $orders
     * @return bool
     */
    private function checkActivationCustomer($orders): bool
    {
        foreach ($orders as $order){
            if ($order['type_option'] === 'newCustomer' && $order['status_option'] === 'completed'){
                return true;
            }
        }
        return false;
    }

    /**
     * if active subscription is found, return true
     *
     * @param $active
     * @return bool
     */
    private function checkActiveSubscription($active): bool
    {
        return $active === '1';
    }

    /**
     * if no invoice is not paid for longer then 30 days, return true
     *
     * @param $invoices
     * @return bool
     */
    private function checkPaidInvoice($invoices): bool
    {
        foreach ($invoices as $invoice) {
            if ($invoice['paid'] === '0') {
                $dateNow = time();
                $invoiceDate = strtotime($invoice['invoice_date']);
                $daysDiff = $dateNow - $invoiceDate;
                $days = (int)floor($daysDiff / (60 * 60 * 24));
                if ($days > 30) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * if no pending sim activation is found, return true
     *
     * @param $orders
     * @return bool
     */
    private function checkPendingSimActivation($orders): bool
    {
        foreach ($orders as $order) {
            if ($order['type_option'] === 'activateSim' && $order['status_option'] === 'completed' ) {
                return true;
            }
        }
        return false;
    }

    /**
     * if less then 2 activations are found in 30 days, return true
     *
     * @param $orders
     * @return bool
     */
    private function checkSimActivations($orders): bool
    {
        $count = 0;
        foreach ($orders as $order) {
            if ($order['type_option'] === 'activateSim'){
                $dateNow = time();
                $OrderDate = strtotime($order['order_date']);
                $daysDiff = $dateNow - $OrderDate;
                $days = (int)floor($daysDiff / (60 * 60 * 24));
                if ($days < 30) {
                    ++$count;
                }
            }
        }
        return !($count >= 2);
    }

    /**
     * if completed sim delivery in less then 30 days is found, return true
     *
     * @param $orders
     * @return bool
     */
    private function hasNewSimCard($orders): bool
    {
        foreach ($orders as $order) {
            if (($order['type_option'] === 'newSim' || $order['type_option'] === 'renewal') && $order['status_option'] === 'completed') {
                $dateNow = time();
                $orderDate = strtotime($order['order_date']);
                $daysDiff = $dateNow - $orderDate;
                $days = (int)floor($daysDiff / (60 * 60 * 24));
                if ($days < 30) {
                    return true;
                }
            }
        }
        return false;
    }
}