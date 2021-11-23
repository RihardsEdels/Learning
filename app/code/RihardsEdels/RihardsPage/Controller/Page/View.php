<?php

declare(strict_types=1);

namespace RihardsEdels\RihardsPage\Controller\Page;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;

class View extends Action
{
    public function execute()
    {
        /** @var Json $jsonResult */
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $jsonResult->setData([
            'I am learning new things'
        ]);
        return $jsonResult;
    }
}