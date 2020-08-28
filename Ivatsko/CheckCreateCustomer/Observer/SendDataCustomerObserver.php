<?php
declare(strict_types=1);

namespace Ivatsko\CheckCreateCustomer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Ivatsko\CheckCreateCustomer\Sender\SendEmail;
use Ivatsko\CheckCreateCustomer\Sender\SendLog;

/**
 * Class SendDataCustomerObserver
 * @package Ivatsko\CheckCreateCustomer\Observer
 */
class SendDataCustomerObserver implements ObserverInterface
{
    /**
     * @var SendLog
     */
    private $senderLog;
    /**
     * @var SendEmail
     */
    private $senderEmail;

    /**
     * SendDataCustomerObserver constructor.
     * @param SendLog $senderLog
     * @param SendEmail $senderEmail
     */
    public function __construct(
        SendLog $senderLog,
        SendEmail $senderEmail
    ){
        $this->senderLog = $senderLog;
        $this->senderEmail = $senderEmail;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $this->senderLog->logCustomerData($customer);
        $this->senderEmail->sendCustomerData($customer);
    }
}
