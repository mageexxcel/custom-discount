<?php
namespace Excellence\CustomOrderDiscount\Model\Total\Quote;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;

/**
 * Class Custom
 * @package Excellence\CustomOrderDiscount\Model\Total\Quote
 */
class Custom extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    const AREA_CODE = \Magento\Framework\App\Area::AREA_ADMINHTML;

    protected $baseDiscount;
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_priceCurrency;
    /**
     * Custom constructor.
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\State $state,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_state = $state;
        $this->_priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->currencyFactory = $currencyFactory;
    }
    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|bool
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $this->clearValues($total);

        // check if admin
        $areaCode = $this->_state->getAreaCode();
        if ($areaCode != self::AREA_CODE) {
            return $this;
        }
        $items = $shippingAssignment->getItems();
        if (!count($items)) {
            return $this;
        }

        $this->baseDiscount = 00.00;
        $discountData = $this->_checkoutSession->getCustomDiscountData();
        $baseSubtotalIncTax = $total->getData('base_subtotal_incl_tax') ? $total->getData('base_subtotal_incl_tax') : $total->getData('base_subtotal_total_incl_tax');
        $baseGrandTotal = $baseSubtotalIncTax + $total->getData('base_shipping_incl_tax');

        if (!empty($discountData)) {
            if ($discountData['type'] == 'fixed') {
                $this->baseDiscount = (float)$discountData['discount_value'];
            } else {
                $this->baseDiscount = ($baseGrandTotal * (float)$discountData['discount_value'])/100;
            }
        }
        $currentCurrency = $quote->getStore()->getCurrentCurrency()->getCode();

        $baseCurrency = $quote->getStore()->getBaseCurrency()->getCode();

        $rate = $this->currencyFactory->create()->load($currentCurrency)->getAnyRate($baseCurrency);

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');


        $discount = $this->baseDiscount * $rate;
        $total->setGrandTotal($total->getGrandTotal() - $this->baseDiscount);
        $total->setBaseGrandTotal($total->getBaseGrandTotal() - $discount);
        $quote->setBaseCustomDiscount($discount);
        $quote->setCustomDiscount($this->baseDiscount);
        $this->_checkoutSession->setBaseCustomDiscount($discount);
        $this->_checkoutSession->setCustomDiscount($this->baseDiscount);
        return $this;
    }

    /**
     * @param Total $total
     */
    protected function clearValues(Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }

    

    /**
     * @param Quote $quote
     * @param Total $total
     * @return array
     */
    public function fetch(Quote $quote, Total $total)
    {
        // check if admin
        $areaCode = $this->_state->getAreaCode();
        if ($areaCode != self::AREA_CODE) {
            return;
        }
        return [
            'code' => $this->getCode(),
            'title' => 'Custom Discount',
            'value' => $this->baseDiscount
        ];
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Custom Discount');
    }
}
