<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Excellence\CustomOrderDiscount\Controller\Adminhtml\SaveFormData;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\InputException;
use Psr\Log\LoggerInterface;

/**
 * Adminhtml sales orders controller
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Index extends \Magento\Backend\App\Action
{
    protected $resultJsonFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Action\Context $context
     * @param LoggerInterface $logger
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        Action\Context $context,
        JsonFactory $resultJsonFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $postData = $this->getRequest()->getPostValue();
        if($postData) {
            $this->_checkoutSession->setCustomDiscountData($postData);
            $postData['message'] = 'success';
            $postData['status'] = 200;
            $result->setData($postData);
        } else {
            throw new \Exception("No value found");
        }
        
        return $result; 
    }
}
