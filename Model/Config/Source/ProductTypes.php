<?php
/**
 * Copyright © element119. All rights reserved.
 * See LICENCE.txt for licence details.
 */
declare(strict_types=1);

namespace Element119\ProductTypeAttributeManager\Model\Config\Source;

use Magento\Catalog\Model\ProductTypes\Config as ProductTypesConfig;
use Magento\Framework\Data\OptionSourceInterface;

class ProductTypes implements OptionSourceInterface
{
    public function __construct(
        private readonly ProductTypesConfig $productTypesConfig,
    ) {
    }

    public function toOptionArray(): array
    {
        $productTypes = [];

        foreach ($this->productTypesConfig->getAll() as $type => $data) {
            $productTypes[] = [
                'value' => $type,
                'label' => $data['label'],
            ];
        }

        return $productTypes;
    }
}
