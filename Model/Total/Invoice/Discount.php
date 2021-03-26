<?php
namespace Excellence\CustomOrderDiscount\Model\Total\Invoice;

/**
 * Class Custom
 * @package Excellence\CustomOrderDiscount\Model\Total\Invoice\Discount
 */
class Discount extends \Magento\Sales\Model\Order\Invoice\Total\AbstractTotal
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
     * Collect Weee amounts for the invoice
     *
     * @param  \Magento\Sales\Model\Order\Invoice $invoice
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        $store = $invoice->getStore();
        $order = $invoice->getOrder();

        $invoice->setGrandTotal($invoice->getGrandTotal() - $order->getExcCustomdiscountAmt());
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $order->getBaseExcCustomdiscountAmt());
        $invoice->setExcCustomdiscountAmt($order->getExcCustomdiscountAmt());
        $invoice->setBaseExcCustomdiscountAmt($order->getBaseExcCustomdiscountAmt());
        return $this;
    }
}