<?php

declare(strict_types=1);

namespace YaroslavN\RegularCustomer\ViewModel\Customer;

use YaroslavN\RegularCustomer\Model\ResourceModel\DiscountRequest\CollectionFactory as DiscountRequestCollectionFactory;
use YaroslavN\RegularCustomer\Model\ResourceModel\DiscountRequest\Collection as DiscountRequestCollection;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\Product;
use Magento\Store\Model\Website;

class RequestList implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var DiscountRequestCollectionFactory $discountRequestCollectionFactory
     */
    private DiscountRequestCollectionFactory $discountRequestCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    private \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private \Magento\Store\Model\StoreManagerInterface $storeManager;

    /**
     * @var DiscountRequestCollection $loadedDiscountRequestCollection
     */
    private DiscountRequestCollection $loadedDiscountRequestCollection;

    /**
     * @var ProductCollection $loadedProductCollection
     */
    private ProductCollection $loadedProductCollection;

    /**
     * @param DiscountRequestCollectionFactory $discountRequestCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        DiscountRequestCollectionFactory $discountRequestCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->discountRequestCollectionFactory = $discountRequestCollectionFactory;
        $this->storeManager = $storeManager;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * Get a list of customer discount requests
     *
     * @return DiscountRequestCollection
     */
    public function getDiscountRequestCollection(): DiscountRequestCollection
    {
        if (isset($this->loadedDiscountRequestCollection)) {
            return $this->loadedDiscountRequestCollection;
        }

        /** @var Website $website */
        $website = $this->storeManager->getWebsite();

        /** @var DiscountRequestCollection $collection */
        $collection = $this->discountRequestCollectionFactory->create();
        // @TODO: get current customer's ID
        // $collection->addFieldToFilter('customer_id', 2);
        // @TODO: check if accounts are shared per website or not
        $collection->addFieldToFilter('store_id', ['in' => $website->getStoreIds()]);
        $this->loadedDiscountRequestCollection = $collection;

        return $this->loadedDiscountRequestCollection;
    }

    /**
     * Get product for customer discount request
     *
     * @param int $productId
     * @return Product|null
     */
    public function getProduct(int $productId): ?Product
    {
        if (isset($this->loadedProductCollection)) {
            return $this->loadedProductCollection->getItemById($productId);
        }

        $discountRequestCollection = $this->getDiscountRequestCollection();
        $productIds = array_unique(array_filter($discountRequestCollection->getColumnValues('product_id')));

        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToFilter('entity_id', $productIds)
            ->addAttributeToSelect('name')
            ->addWebsiteFilter();
        $this->loadedProductCollection = $productCollection;

        return $this->loadedProductCollection->getItemById($productId);
    }
}
