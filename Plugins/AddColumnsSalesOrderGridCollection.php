<?php 
namespace Excellence\CustomOrderDiscount\Plugins;

use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as SalesOrderGridCollection;

class AddColumnsSalesOrderGridCollection
{
    private $messageManager;
    private $collection;

    public function __construct(MessageManager $messageManager,
        SalesOrderGridCollection $collection
    ) {

        $this->messageManager = $messageManager;
        $this->collection = $collection;
    }

    public function aroundGetReport(
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $subject,
        \Closure $proceed,
        $requestName    
    ) {        
        $result = $proceed($requestName);
        if ($requestName == 'sales_order_grid_data_source') {
            if ($result instanceof $this->collection
            ) { 
                $select = $this->collection->getSelect();
                $select->join(
                    ["sst" => "sales_order"],
                    'main_table.entity_id = sst.entity_id',
                    'sst.base_exc_customdiscount_amt'
                )
                ->distinct();                                   
            }

        }
        return $this->collection;
    }
}