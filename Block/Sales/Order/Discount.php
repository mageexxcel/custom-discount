<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Tax totals modification block. Can be used just as subblock of \Magento\Sales\Block\Order\Totals
 */
namespace Excellence\CustomOrderDiscount\Block\Sales\Order;

class Discount extends \Magento\Framework\View\Element\Template
{

    /**
     * @var Order
     */
    protected $_order;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context, $data);
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->_source;
    }

    public function getStore()
    {
        return $this->_order->getStore();
    }

      /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }

    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();

        if (
            (!$this->_source->getExcCustomdiscountAmt() || empty($this->_source->getExcCustomdiscountAmt())) &&
            (!$this->_checkoutSession->getCustomDiscount() || empty($this->_checkoutSession->getCustomDiscount()))
            ) {
            return $this;
        }

        $value = $this->_source->getExcCustomdiscountAmt() ? $this->_source->getExcCustomdiscountAmt() : $this->_checkoutSession->getCustomDiscount();
        $baseValue = $this->_source->getBaseExcCustomdiscountAmt() ? $this->_source->getBaseExcCustomdiscountAmt() : $this->_checkoutSession->getBaseCustomDiscount();

        $store = $this->getStore();

        $exc_customdiscount_amt = new \Magento\Framework\DataObject(
            [
                'code' => 'exc_customdiscount_amt',
                'strong' => false,
                'value' => $value,
                'base_value' => $baseValue,
                'label' => __('Custom Discount'),
            ]
        );
        $parent->addTotal($exc_customdiscount_amt, 'exc_customdiscount_amt');
        return $this;
    }

}