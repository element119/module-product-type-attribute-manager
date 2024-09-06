<?php
/**
 * Copyright © element119. All rights reserved.
 * See LICENCE.txt for licence details.
 */
declare(strict_types=1);

namespace Element119\ProductTypeAttributeManager\Plugin;

use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\Catalog\Controller\Adminhtml\Product\Attribute\Save as ProductAttributeSave;
use Magento\Framework\App\RequestInterface;

class SaveAttributeProductTypes
{
    public function __construct(
        private readonly RequestInterface $request,
    ) {
    }

    public function beforeExecute(ProductAttributeSave $subject): void
    {
        $postData = $this->request->getPostValue();

        if (!array_key_exists(EavAttributeInterface::APPLY_TO, $postData)) {
            /**
             * The use of a single space for the value tricks Magento into thinking the attribute belongs to no product
             * types. An otherwise falsey value has the opposite effect - instead applying the attribute to ALL types.
             *
             * The Magento_Catalog:etc/product_types.xsd does not validate that product type names do not contain
             * whitespace or consist of only whitespace. Therefore, while highly unlikely, it is technically possible
             * for a product type name to be declared as a single space - meaning the trick below will result in the
             * attribute being applied to that product type.
             *
             * If you somehow find yourself in this position:
             *     1. I'm sorry that you have to work on a project where somebody has defined a product type like this.
             *        I can only imagine some of the other horrors you're dealing with.
             *     2. The fix to work around this is to simply change this single space to be any non-falsey value that
             *        is also NOT a defined product type.
             */
            $postData[EavAttributeInterface::APPLY_TO] = ' ';
            $this->request->setPostValue($postData);
        }
    }
}
