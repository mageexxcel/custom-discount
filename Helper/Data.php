<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Excellence\CustomOrderDiscount\Helper;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Directory\Model\CurrencyFactory;

/**
 * Data helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var CurrencyFactory
     */
    private $currencyFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Eav\Model\Entity\Attribute\Config $attributeConfig
     * @param \Magento\Eav\Model\Config $eavConfig
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        CurrencyFactory $currencyFactory
    ) {
        $this->currencyFactory = $currencyFactory->create();
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getCurrencySymbol($store)
    {
        $currentCurrency = $store->getCurrentCurrencyCode();
        $currency = $this->currencyFactory->load($currentCurrency);
        return $currency->getCurrencySymbol();
    }

    public function getCustomDiscountData()
    {
        return $this->_checkoutSession->getCustomDiscountData();
    }
}