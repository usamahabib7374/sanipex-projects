<?php

namespace Sanipex\Projects\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface {

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;
        $installer->startSetup();


        $tableName = $installer->getTable('sanipex_projects_content');

        if ($installer->getConnection()->isTableExists($tableName) != true) {

            $table = $installer->getConnection()
                    ->newTable($tableName)
                    ->addColumn(
                            'id', Table::TYPE_INTEGER, null, [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                            ], 'ID'
                    )
                    ->addColumn(
                            'title', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Title'
                    )
                    ->addColumn(
                            'url_key', Table::TYPE_TEXT, null, ['nullable' => false, 'LENGTH' => 255, 'default' => ''], 'Url-Key'
                    )
                    ->addColumn(
                            'short_description', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Short Description'
                    )
                    ->addColumn(
                            'description', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Description'
                    )
                    ->addColumn(
                            'main-image', Table::TYPE_TEXT, null, ['nullable' => false, 'LENGTH' => 255, 'default' => ''], 'Main Image'
                    )
                    ->addColumn(
                            'project-type', Table::TYPE_TEXT, null, ['nullable' => false, 'LENGTH' => 255, 'default' => ''], 'Project Type'
                    )
                    ->addColumn(
                            'project-country', Table::TYPE_TEXT, null, ['nullable' => false, 'LENGTH' => 255, 'default' => ''], 'Project Type'
                    )
                    ->addColumn(
                            'status', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'], 'Status'
                    )
                    ->addColumn(
                            'completion_date', Table::TYPE_DATETIME, null, ['nullable' => false], 'Project Compelition Date'
                    )
                    ->setComment('Projects Table')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }


        $tableName2 = $installer->getTable('sanipex_projects_content_store');

        if ($installer->getConnection()->isTableExists($tableName2) != true) {

            $table = $installer->getConnection()
                    ->newTable($tableName2)
                    ->addColumn(
                            'project_id', Table::TYPE_INTEGER, null, [
                        'nullable' => false,
                            ], 'ID'
                    )
                    ->addColumn(
                            'store', Table::TYPE_INTEGER, null, [
                        'default' => 0,
                        'nullable' => false,
                            ], 'Store'
                    )
                    ->setComment('Projects Table Store Mapping')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }

}
