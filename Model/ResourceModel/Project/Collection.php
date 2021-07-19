<?php

namespace Sanipex\Projects\Model\ResourceModel\Project;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection {

    protected $_idFieldName = 'id';

    protected function _construct() {
        $this->_init('Sanipex\Projects\Model\Project', 'Sanipex\Projects\Model\ResourceModel\Project');
    }

    protected function _initSelect() {
        parent::_initSelect();
        $this->getSelect()->join(['u' => $this->getTable('sanipex_projects_content_store')], 'u.project_id = main_table.id', ['u.store'])->group('project_id');
    }

}
