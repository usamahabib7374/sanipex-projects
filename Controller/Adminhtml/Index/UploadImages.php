<?php

namespace Sanipex\Projects\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class UploadImages extends \Magento\Backend\App\Action {

    public $imageUploader;

    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Sanipex\Projects\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    public function _isAllowed() {
        return $this->_authorization->isAllowed('Sanipex_Projects::projects');
    }

    public function execute() {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('main-image');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

}
