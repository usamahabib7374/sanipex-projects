<?php

namespace Sanipex\Projects\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action {

    const ADMIN_RESOURCE = 'Index';

    protected $resultPageFactory;

    public function __construct(
    Context $context, PageFactory $resultPageFactory) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute() {
        return $this->resultPageFactory->create();
    }

}
