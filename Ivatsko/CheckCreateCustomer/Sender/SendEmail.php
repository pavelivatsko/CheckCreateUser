<?php
declare(strict_types=1);

namespace Ivatsko\CheckCreateCustomer\Sender;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Escaper;
use \Magento\Framework\Message\ManagerInterface;

/**
 * Class SendEmail
 * @package Ivatsko\CheckCreateCustomer\Sender
 */
class SendEmail
{

    const XML_PATH_CUSTOMER_SUPPORT_EMAIL_ADDRESS = 'trans_email/ident_support/email';
    const NAME_SENDER_EMAIL = 'humor02@gmail.com';

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Escaper
     */
    private $escaper;
    /**
     * @var ManagerInterface
     */
    private $messageManager;


    /**
     * SendEmail constructor.
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param Escaper $escaper
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Escaper $escaper,
        ManagerInterface $messageManager
    ){
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
        $this->messageManager = $messageManager;
    }


    /**
     * @param CustomerInterface $customer
     */
    public function sendCustomerData(CustomerInterface $customer)
    {

        $this->inlineTranslation->suspend();

        try {
            $customerSupportEmail = $this->scopeConfig->getValue(self::XML_PATH_CUSTOMER_SUPPORT_EMAIL_ADDRESS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $customerData = ['firstName' => $customer->getFirstName(), 'lastName' => $customer->getLastName(), 'email' => $customer->getEmail()];
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($customerData);
            $error = false;

            $sender = [
                'name' => $this->escaper->escapeHtml('Test'),
                'email' => $this->escaper->escapeHtml(self::NAME_SENDER_EMAIL),
            ];


            $transport = $this->transportBuilder
                ->setTemplateIdentifier('send_email_email_template')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($sender)
                ->addTo($customerSupportEmail)
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
            $this->messageManager->addSuccess(
                __('Message sent successfully')
            );
            return;
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
            $this->messageManager->addError(__('Error Message sent' . $e->getMessage())
            );
            return;
        }
    }
}
