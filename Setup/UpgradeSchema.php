<?php

namespace Sanipex\Projects\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface {

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $setup->getConnection()->addColumn(
                    $setup->getTable("sanipex_projects_content"), 'thumbnail', [
                "type" => Table::TYPE_TEXT,
                "comment" => "Thumbnail image"
                    ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $setup->getConnection()->addColumn(
                    $setup->getTable("sanipex_projects_content"), 'featured', [
                "type" => Table::TYPE_SMALLINT,
                "comment" => "Featured"
                    ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.5') < 0) {
            $connection = $setup->getConnection();
            $connection->dropTable($connection->getTableName('sanipex_projects_content_store'));
            $tableName2 = $setup->getTable('sanipex_projects_content_store');
            if ($setup->getConnection()->isTableExists($tableName2) != true) {

                $table = $setup->getConnection()
                        ->newTable($tableName2)
                        ->addColumn(
                                'link_id', Table::TYPE_INTEGER, null, [
                            'identity' => true,
                            'unsigned' => true,
                            'nullable' => false,
                            'primary' => true
                                ], 'ID'
                        )
                        ->addColumn(
                                'project_id', Table::TYPE_INTEGER, null, ['unsigned' => true, 'nullable' => false, 'default' => '0'], 'Project Id'
                        )
                        ->addColumn(
                                'store', Table::TYPE_SMALLINT, null, ['unsigned' => true, 'nullable' => false, 'default' => '0'], 'Store Id'
                        )
                        ->addForeignKey(
                                $setup->getFkName(
                                        'sanipex_projects_content_store', 'store', 'store', 'store_id'
                                ), 'store', $setup->getTable('store'), 'store_id', Table::ACTION_CASCADE
                        )
                        ->addForeignKey(
                                $setup->getFkName(
                                        'sanipex_projects_content_store', 'project_id', 'sanipex_projects_content', 'id'
                                ), 'project_id', $setup->getTable('sanipex_projects_content'), 'id', Table::ACTION_CASCADE
                        )
                        ->setComment('Projects Table Store Mapping')
                        ->setOption('type', 'InnoDB')
                        ->setOption('charset', 'utf8');
                $setup->getConnection()->createTable($table);
            }
        }
        $setup->endSetup();
    }

}

?>