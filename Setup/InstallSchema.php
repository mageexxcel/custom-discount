<?php


namespace Excellence\CustomOrderDiscount\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();
 
        if ($connection->tableColumnExists('sales_order', 'exc_customdiscount_amt') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'exc_customdiscount_amt',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 10,
                        'comment' => 'Custom Discount'
                    ]
                );
        }

        if ($connection->tableColumnExists('sales_order', 'base_exc_customdiscount_amt') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'base_exc_customdiscount_amt',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 10,
                        'comment' => 'Custom Discount'
                    ]
                );
        }

        if ($connection->tableColumnExists('sales_order_grid', 'exc_customdiscount_amt') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order_grid'),
                    'exc_customdiscount_amt',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 10,
                        'comment' => 'Custom Discount'
                    ]
                );
        }

        if ($connection->tableColumnExists('sales_order_grid', 'base_exc_customdiscount_amt') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order_grid'),
                    'base_exc_customdiscount_amt',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 10,
                        'comment' => 'Custom Discount'
                    ]
                );
        }

        if ($connection->tableColumnExists('sales_invoice', 'exc_customdiscount_amt') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_invoice'),
                    'exc_customdiscount_amt',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 10,
                        'comment' => 'Custom Discount'
                    ]
                );
        }

        if ($connection->tableColumnExists('sales_invoice', 'base_exc_customdiscount_amt') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_invoice'),
                    'base_exc_customdiscount_amt',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 10,
                        'comment' => 'Custom Discount'
                    ]
                );
        }

        if ($connection->tableColumnExists('sales_creditmemo', 'exc_customdiscount_amt') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_creditmemo'),
                    'exc_customdiscount_amt',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 10,
                        'comment' => 'Custom Discount'
                    ]
                );
        }

        if ($connection->tableColumnExists('sales_creditmemo', 'base_exc_customdiscount_amt') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_creditmemo'),
                    'base_exc_customdiscount_amt',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 10,
                        'comment' => 'Custom Discount'
                    ]
                );
        }
        
        $installer->endSetup();
    }
}
