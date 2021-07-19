<?php

namespace Sanipex\Projects\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Project extends AbstractModel implements IdentityInterface {

    const CACHE_TAG = 'sanipex_projects_content';

    protected function _construct() {
        $this->_init('Sanipex\Projects\Model\ResourceModel\Project');
    }

    public function getIdentities() {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

}
