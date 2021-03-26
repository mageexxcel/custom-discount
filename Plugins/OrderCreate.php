<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Excellence\CustomOrderDiscount\Plugins;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Model\Metadata\Form as CustomerForm;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Item;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Model\Order;

/**
 * Order create model
 * @api
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 100.0.2
 */
class OrderCreate extends \Magento\Sales\Model\AdminOrder\Create
{
    /**
     * Create new order
     *
     * @return \Magento\Sales\Model\Order
     */
    public function aroundCreateOrder()
    {
        $this->_prepareCustomer();
        $this->_validate();
        $quote = $this->getQuote();
        $this->_prepareQuoteItems();

        $orderData = [];
        if ($this->getSession()->getOrder()->getId()) {
            $oldOrder = $this->getSession()->getOrder();
            $originalId = $oldOrder->getOriginalIncrementId();
            if (!$originalId) {
                $originalId = $oldOrder->getIncrementId();
            }
            $orderData = [
                'original_increment_id' => $originalId,
                'relation_parent_id' => $oldOrder->getId(),
                'relation_parent_real_id' => $oldOrder->getIncrementId(),
                'edit_increment' => $oldOrder->getEditIncrement() + 1,
                'increment_id' => $originalId . '-' . ($oldOrder->getEditIncrement() + 1)
            ];
            $quote->setReservedOrderId($orderData['increment_id']);
        }
        $order = $this->quoteManagement->submit($quote, $orderData);

        if ($this->getSession()->getOrder()->getId()) {
            $oldOrder = $this->getSession()->getOrder();
            $oldOrder->setRelationChildId($order->getId());
            $oldOrder->setRelationChildRealId($order->getIncrementId());
            $oldOrder->save();
            $this->orderManagement->cancel($oldOrder->getEntityId());
            $order->save();
        }
        if($order->getid()) {
            $baseDiscountAmount = $quote->getBaseCustomDiscount();
            $discountAmount = $quote->getCustomDiscount();
            if($order->setBaseExcCustomdiscountAmt($baseDiscountAmount)->setExcCustomdiscountAmt($discountAmount)->save()) {
                $this->_session->setCustomDiscountSaved(1);
            }
        }
        if ($this->getSendConfirmation()) {
            $this->emailSender->send($order);
        }

        $this->_eventManager->dispatch('checkout_submit_all_after', ['order' => $order, 'quote' => $quote]);

        return $order;
    }
}