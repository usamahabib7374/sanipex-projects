<?php

namespace Sanipex\Projects\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

class ProjectTypes implements ArrayInterface {

    const Hospitality = "hospitality";
    const Commercial = "commercial & residential";
    const Institutional = "institutional";

    public function toOptionArray() {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    public static function getOptionArray() {
        return [self::Hospitality => __('Hospitality'), self::Commercial => __('Commercial & Residential'), self::Institutional => __('Institutional')];
    }

    public function getAllOptions() {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    public function getOptionText($optionId) {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }

}
