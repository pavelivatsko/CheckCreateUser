<?php
declare(strict_types=1);

namespace Ivatsko\CheckCreateCustomer\Sender;

use Ivatsko\CheckCreateCustomer\Logger\Logger as CustomLogger;
use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Class SendLog
 * @package Ivatsko\CheckCreateCustomer\Sender
 */
class SendLog
{
    /**
     * @var CustomLogger
     */
    private $customLogger;

    /**
     * SendLog constructor.
     * @param CustomLogger $logger
     */
    public function __construct(
        CustomLogger $logger
    ){
        $this->customLogger = $logger;
    }

    /**
     * @param CustomerInterface $customer
     */
    public function logCustomerData(CustomerInterface $customer) {
        $currentDate = date("F j, Y, H:i:s");
        $logerLogString = "Current time: " . $currentDate . "," . "Customer First Name: " . $customer->getFirstName() . "," . "Customer Last Name: " . $customer->getLastName() . "," . "Customer Email: " . $customer->getEmail();
        $this->customLogger->info($logerLogString);
    }

}
