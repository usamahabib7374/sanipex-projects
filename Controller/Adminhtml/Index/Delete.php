<?php

namespace Sanipex\Projects\Controller\Adminhtml\Index;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Sanipex\Projects\Model\ProjectFactory;

class Delete extends Action {

    const ADMIN_RESOURCE = 'Index';

    protected $resultPageFactory;
    protected $projectFactory;

    public function __construct(
    Context $context, PageFactory $resultPageFactory, ProjectFactory $projectFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->projectFactory = $projectFactory;
        parent::__construct($context);
    }

    public function execute() {
        $id = $this->getRequest()->getParam('id');

        $project = $this->projectFactory->create()->load($id);

        if (!$project) {
            $this->messageManager->addError(__('Unable to process. please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/', array('_current' => true));
        }

        try {
            $project->delete();
            $this->messageManager->addSuccess(__('Your project has been deleted !'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Error while trying to delete project'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }

}
