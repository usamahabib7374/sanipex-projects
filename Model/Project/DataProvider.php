<?php

namespace Sanipex\Projects\Model\Project;

use Sanipex\Projects\Model\ResourceModel\Project\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends AbstractDataProvider {

    protected $collection;
    protected $_loadedData;

    public function __construct(
    $name, $primaryFieldName, $requestFieldName, CollectionFactory $projectCollectionFactory, StoreManagerInterface $storeManager, array $meta = [], array $data = []
    ) {
        $this->collection = $projectCollectionFactory->create();
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData() {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $project) {
            $_data = $project->getData();
            $project->load($project->getId());
            if (isset($_data['thumbnail'])) {
                $image = [];
                $image[0]['name'] = $project->getData('thumbnail');
                $image[0]['url'] = $this->getMediaUrl() . $project->getData('thumbnail');
                $_data['thumbnail'] = $image;
            }
            if ($_data['main-image'] !== "") {
                if (!is_null($_data['main-image'])) {
                    $imagesNames = json_decode($_data['main-image'], true);

                    $mainImages = [];
                    $i = 0;
                    foreach ($imagesNames as $image) {
                        $mainImages[$i]['name'] = $image;
                        $mainImages[$i]['url'] = $this->getMediaUrl() . $image;
                        $i++;
                    }
                    $_data['main-image'] = $mainImages;
                }
            }
            if (isset($_data['store'])) {
                $_data['store'] = $this->getStoreIds($project->getId());
            }
            $project->setData($_data);
            $this->_loadedData[$project->getId()] = $_data;
        }

        return $this->_loadedData;
    }

    private function getStoreIds($projectId) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $query = "Select store from sanipex_projects_content_store where project_id  = $projectId";
        $data = $connection->fetchAll($query);
        $return = [];
        foreach ($data as $row) {
            $return[] = $row['store'];
        }
        return $return;
    }

    public function getMediaUrl() {
        $mediaUrl = $this->storeManager->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'projects/tmp/projects/';
        return $mediaUrl;
    }

}
