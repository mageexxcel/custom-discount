<?php
namespace Excellence\CustomOrderDiscount\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class CheckoutSubmitObserver implements ObserverInterface
{
    private $quote = null;
    protected $order;
    protected $checkoutSession;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Backend\Model\Session\Quote $quoteSession,
        \Magento\Sales\Model\Order $order
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_session = $quoteSession;
        $this->order = $order;
    }

    public function execute(EventObserver $observer)
    {
        $this->quote = $observer->getQuote();
        $orderId = $observer->getOrder()->getId();
        $order = $this->order->load($orderId);
        $baseDiscountAmount = $this->quote->getBaseCustomDiscount();
        $discountAmount = $this->quote->getCustomDiscount();
        if($order->setBaseExcCustomdiscountAmt($baseDiscountAmount)->save() && $order->setExcCustomdiscountAmt($discountAmount)->save()) {
            $this->_checkoutSession->unsCustomDiscountData();
            $this->_checkoutSession->unsCustomDiscount();
            $this->_checkoutSession->unsBaseCustomDiscount();
        }
    }
} 