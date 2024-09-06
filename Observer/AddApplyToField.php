<?php
/**
 * Copyright © element119. All rights reserved.
 * See LICENCE.txt for licence details.
 */
declare(strict_types=1);

namespace Element119\ProductTypeAttributeManager\Observer;

use Element119\ProductTypeAttributeManager\Model\Config\Source\ProductTypes;
use Element119\ProductTypeAttributeManager\Model\ResourceModel\EavAttributeResource;
use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\Data\Form;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\App\RequestInterface;

class AddApplyToField implements ObserverInterface
{
    public function __construct(
        private readonly ProductTypes $productTypes,
        private readonly EavAttributeResource $eavAttributeResource,
        private readonly RequestInterface $request,
    ) {
    }

    public function execute(Observer $observer): void
    {
        $attributeId = $this->request->getParam(AttributeInterface::ATTRIBUTE_ID);

        if (!isset($attributeId) || !$this->eavAttributeResource->isSystemAttribute((int)$attributeId)) {
            return;
        }

        /** @var Form $form */
        $form = $observer->getForm();

        if (!$form) {
            return;
        }

        $fieldset = $form->getElement('base_fieldset');

        if (!$fieldset) {
            return;
        }

        $fieldset->addField(
            EavAttributeInterface::APPLY_TO,
            'multiselect',
            [
                'name' => EavAttributeInterface::APPLY_TO,
                'label' => __('Apply To Product Types'),
                'title' => __('Apply To Product Types'),
                'note' => __('Attribute will be applied to selected product types.'),
                'values' => $this->productTypes->toOptionArray(),
            ]
        );
    }
}
