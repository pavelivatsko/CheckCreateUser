<?php
declare(strict_types=1);

namespace Ivatsko\CheckCreateCustomer\Logger;

use Monolog\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    private $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    private $fileName = '/var/log/create_customer.log';
}