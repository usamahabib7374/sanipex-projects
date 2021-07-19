<?php

namespace Sanipex\Projects\Controller\Index;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Action\Context;
use Sanipex\Projects\Model\ProjectFactory;
use Magento\Framework\View\Result\PageFactory;

class View extends \Magento\Framework\App\Action\Action {

    protected $_pageFactory;
    protected $_projectsFactory;

    public function __construct(
    Context $context, PageFactory $pageFactory, ProjectFactory $projectsFactory
    ) {
        parent::__construct($context);
        $this->_pageFactory = $pageFactory;
        $this->_projectsFactory = $projectsFactory;
    }

    public function execute() {

        $projectsId = $this->getRequest()->getParam('id');
        $projects = $this->_projectsFactory->create()->load($projectsId);

        $this->_objectManager->get('Magento\Framework\Registry')
                ->register('projectsData', $projects);

        $pageFactory = $this->_pageFactory->create();
        $pageFactory->getConfig()->getTitle()->set($projects->getTitle());
        $breadcrumbs = $pageFactory->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb('home', [
            'label' => __('Home'),
            'title' => __('Home'),
            'link' => $this->_url->getUrl('')
                ]
        );

        $breadcrumbs->addCrumb('project-type', [
            'label' => $projects->getData('project-type'),
            'title' => $projects->getData('project-type'),
            'link' => $this->_url->getUrl("projects/" . $projects->getData('project-type'))
                ]
        );
        $breadcrumbs->addCrumb('projects', [
            'label' => $projects->getTitle(),
            'title' => $projects->getTitle()
                ]
        );

        return $pageFactory;
    }

}
