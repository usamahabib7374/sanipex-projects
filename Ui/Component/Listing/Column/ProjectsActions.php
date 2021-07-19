<?php

namespace Sanipex\Projects\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ProjectsActions extends Column {

    protected $urlBuilder;

    public function __construct(
    ContextInterface $context, UiComponentFactory $uiComponentFactory, UrlInterface $urlBuilder, array $components = [], array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl('projects/index/edit', ['id' => $item['id']]),
                    'label' => __('Edit'),
                    'hidden' => false
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl('projects/index/delete', ['id' => $item['id']]),
                    'label' => __('Delete'),
                    'hidden' => false
                ];
            }
        }

        return $dataSource;
    }

}
