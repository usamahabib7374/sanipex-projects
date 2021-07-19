<?php

namespace Sanipex\Projects\Block;

use Magento\Framework\View\Element\Template;
use Sanipex\Projects\Model\ProjectFactory;
use Magento\Framework\UrlInterface;

class Index extends \Magento\Framework\View\Element\Template {

    protected $_projectsFactory;
    protected $_countyrFactory;
    protected $_resource;
    public $_storeManager;

    public function __construct(
    Template\Context $context, ProjectFactory $projectsFactory, \Magento\Framework\App\ResourceConnection $Resource, \Magento\Directory\Model\CountryFactory $countryFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, array $data = []
    ) {
        $this->_projectsFactory = $projectsFactory;
        $this->_countryFactory = $countryFactory;
        $this->_resource = $Resource;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    protected function _construct() {
        parent::_construct();
        $collection = $this->getProjectsCollection();
        $this->setCollection($collection);
    }

    private function getProjectsCollection() {
        $storeID = $this->_storeManager->getStore()->getStoreId();
        $params = $this->getRequest()->getParams();
        
        $types = ['hospitality' => 'hospitality', 'institutional' => 'institutional', 'commercial-residental' => 'commercial & residential'];
        $collection = $this->_projectsFactory->create()->getCollection()
                ->addFieldToFilter('store', array(
                    array('finset' => array('0')),
                    array('finset' => array($storeID)),
                        )
                )
                ->setOrder('completion_date', 'DESC');

        if (count($params) > 0) {
            
            $projectType = array_keys($params);
            $pType= $types[$projectType[0]];
            $collection = $this->_projectsFactory->create()->getCollection()
                    ->addFieldToFilter('store', array(
                        array('finset' => array('0')),
                        array('finset' => array($storeID)),
                            )
                    )
                    ->addFieldToFilter('project-type', array('eq' => "$pType"))
                    ->setOrder('completion_date', 'DESC');
        }
        return $collection;
    }

    public function getCountryArr() {
        $countryArr = [];
        $projectsCollection = $this->getProjectsCollection();
        foreach ($projectsCollection as $projects) {
            $countryArr[$projects->getData('project-country')] = $this->getCountryname($projects->getData('project-country'));
        }
        return $countryArr;
    }

    public function getCountryname($countryCode) {
        $country = $this->_countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }

}
