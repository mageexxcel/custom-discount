<?php


namespace Excellence\CustomOrderDiscount\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        $connection = $setup->getConnection();
        $grid = $setup->getTable('sales_order_grid');
        $order = $setup->getTable('sales_order');

        $connection->query(
            $connection->updateFromSelect(
                $connection->select()
                    ->join(
                        $order,
                        sprintf('%s.entity_id = %s.entity_id', $grid, $order),
                        'exc_customdiscount_amt'
                    ),
                $grid
            )
        );
        $setup->endSetup();
    }
}
