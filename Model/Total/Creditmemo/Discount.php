<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Excellence\CustomOrderDiscount\Model\Total\Creditmemo;

use Magento\Sales\Model\Order\Creditmemo;

class Discount extends \Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal
{

    /**
     * Constructor
     *
     * By default is looking for first argument as array and assigns it as object
     * attributes This behavior may change in child classes
     *
     * @param array $data
     */
    public function __construct(
        array $data = []
    ) {
        parent::__construct($data);
    }

    /**
     * Collect Weee amounts for the credit memo
     *
     * @param  Creditmemo $creditmemo
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function collect(Creditmemo $creditmemo)
    {
        $store = $creditmemo->getStore();
        $order = $creditmemo->getOrder();

        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $order->getExcCustomdiscountAmt());
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $order->getBaseExcCustomdiscountAmt());
        $creditmemo->setExcCustomdiscountAmt($order->getExcCustomdiscountAmt());
        $creditmemo->setBaseExcCustomdiscountAmt($order->getBaseExcCustomdiscountAmt());        
        return $this;
    }
}
