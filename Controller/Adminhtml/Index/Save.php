<?php

namespace Sanipex\Projects\Controller\Adminhtml\Index;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Sanipex\Projects\Model\ProjectFactory;

class Save extends Action {

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
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $data = $this->_filterThumbnailData($data);
            $data = $this->_filterMainImagesData($data);

            try {
                $id = $data['id'];

                $project = $this->projectFactory->create()->load($id);

                $data = array_filter($data, function($value) {
                    return $value !== '';
                });

                $project->setData($data);
                $project->save();
                $this->messageManager->addSuccess(__('Successfully saved the item.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $project->getId()]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }

    public function _filterMainImagesData(array $rawData) {
        $data = $rawData;

        if (isset($data['main-image'])) {
            $name = [];
            foreach ($data['main-image'] as $image) {
                $name[] = $image['name'];
            }
            $data['main-image'] = json_encode($name);
        } else {
            $data['main-image'] = null;
        }
        return $data;
    }

    public function _filterThumbnailData(array $rawData) {
        $data = $rawData;
        if (isset($data['thumbnail'][0]['name'])) {
            $data['thumbnail'] = $data['thumbnail'][0]['name'];
        } else {
            $data['thumbnail'] = null;
        }
        return $data;
    }

}
