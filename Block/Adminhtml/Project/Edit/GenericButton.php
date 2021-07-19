<?php

namespace Sanipex\Projects\Block\Adminhtml\Project\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

class GenericButton {

    protected $urlBuilder;
    protected $registry;

    public function __construct(
    Context $context, Registry $registry
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }

    public function getUrl($route = '', $params = []) {
        return $this->urlBuilder->getUrl($route, $params);
    }

}
