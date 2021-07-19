<?php

namespace Sanipex\Projects\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use \Magento\Store\Model\StoreRepository;

class Project extends AbstractDb {

    protected $_storeRepository;

    protected function _construct() {

        $this->_init('sanipex_projects_content', 'id');
    }

    protected function saveStore($object) {
        $condition = $this->getConnection()->quoteInto('project_id = ?', $object->getId());
        $this->getConnection()->delete($this->getTable('sanipex_projects_content_store'), $condition);
        foreach ((array) $object->getData('store') as $store) {
            $storeArray = [
                'project_id' => $object->getId(),
                'store' => $store,
            ];
            $this->getConnection()->insert(
                    $this->getTable('sanipex_projects_content_store'), $storeArray
            );
        }
    }

    protected function saveUrl($object) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_urlRewriteFactory = $objectManager->create('Magento\UrlRewrite\Model\UrlRewriteFactory');
        foreach ((array) $object->getData('store') as $store) {
            $urlRewriteModel = $this->_urlRewriteFactory->create();
            $key = "";
            if ($object->getData('url_key') != '') {
                $key = $object->getData('url_key');
                if ($store == 0) {
                    $storesIds = $this->toOptionArray();
                    foreach ($storesIds as $storeId) {
                        $this->saveStoreRewrites($urlRewriteModel, $storeId, $object->getId(), $key);
                    }
                } else {
                    $this->saveStoreRewrites($urlRewriteModel, $store, $object->getId(), $key);
                }
            }
        }
    }

    private function saveStoreRewrites($urlModel, $storeId, $id, $key) {
        $urlrewrite = $urlModel->getCollection()->addFieldToFilter('store_id', $storeId)->addFieldToFilter('request_path', "projects/" . $key);
        if (is_null($urlrewrite->getFirstItem()->getId())) {
            $urlModel->setStoreId($storeId);
            $urlModel->setIsSystem(0);
            $urlModel->setIdPath(rand(1, 100000));
            $urlModel->setTargetPath("projects/index/view/id/" . $id);
            $urlModel->setRequestPath("projects/" . $key);
            $urlModel->save();
        }
    }

    private function toOptionArray() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_storeRepository = $objectManager->create('Magento\Store\Model\StoreRepository');
        $stores = $this->_storeRepository->getList();
        $storeList = array();
        foreach ($stores as $store) {
            $storeId = $store["store_id"];
            $storeList[$storeId] = $storeId;
        }
        return $storeList;
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object) {
        if (!$object->getIsMassStatus()) {
            $this->saveStore($object);
            $this->saveUrl($object);
        }

        return parent::_afterSave($object);
    }

}
