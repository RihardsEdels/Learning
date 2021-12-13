<?php

namespace Magebit\Attributes\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class Attributes implements ArgumentInterface
{

    private $codes = ["dimensions", "color", "material"];


    public function getAttributes(array $attributes)
    {

        $result = [];
        foreach ($this->codes as $code) {
            if (isset($attributes[$code])) {
                $result[] = $attributes[$code];
                unset($attributes[$code]);
            }
        }

        if (count($result) == count($this->codes)) {
            return $result;
        }

        $difference = count($this->codes) - count($result);

        return array_merge($result, array_slice($attributes, 0, $difference));
    }
}
