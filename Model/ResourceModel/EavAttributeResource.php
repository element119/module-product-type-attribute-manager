<?php
/**
 * Copyright © element119. All rights reserved.
 * See LICENCE.txt for licence details.
 */
declare(strict_types=1);

namespace Element119\ProductTypeAttributeManager\Model\ResourceModel;

use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\App\ResourceConnection;

class EavAttributeResource
{
    public function __construct(
        private readonly ResourceConnection $resourceConnection,
    ) {
    }

    public function isSystemAttribute(int $attributeId): bool
    {
        $catalogConnection = $this->resourceConnection->getConnection('catalog');
        $eavAttributeSelect = $catalogConnection->select()
            ->from(
                $this->resourceConnection->getTableName('eav_attribute'),
                [AttributeInterface::IS_USER_DEFINED]
            )->where(sprintf('attribute_id = %d', $attributeId))
            ->limit(1);
        $result = $catalogConnection->query($eavAttributeSelect)->fetch();

        if ($result && array_key_exists(AttributeInterface::IS_USER_DEFINED, $result)) {
            return (bool)$result[AttributeInterface::IS_USER_DEFINED];
        }

        return false;
    }
}
