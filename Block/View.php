<?php

namespace Sanipex\Projects\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

class View extends Template {

    protected $_coreRegistry;
    protected $_storeManager;

    public function __construct(
    Template\Context $context, Registry $coreRegistry, array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    public function getProjectsInformation() {
        return $this->_coreRegistry->registry('projectsData');
    }

    public function getMediaPath() {
        return $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]).'projects/tmp/projects/';
    }

}
