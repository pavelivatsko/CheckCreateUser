<?php
declare(strict_types=1);

namespace Ivatsko\CheckCreateCustomer\Plugin\Customer;

/**
 * Class CreatePost
 * @package Ivatsko\CheckCreateCustomer\Plugin\Customer
 */
class CreatePost
{

    /**
     * @param \Magento\Customer\Controller\Account\CreatePost $subject
     * @param \Closure $proceed
     * @return mixed
     */
    public function aroundExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        \Closure $proceed
    )
    {
        $firstName = $subject->getRequest()->getParam('firstname');
        $removedSpaces = preg_replace('/\s+/', '', $firstName);
        $subject->getRequest()->setParam('firstname',$removedSpaces);
        $result = $proceed();
        return $result;
    }
}
