<?php
declare(strict_types=1);

namespace Cardoso\CustomerProducts\Model\Products;

use Cardoso\CustomerProducts\Api\Data\ProductRangeInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;

class ProductList implements \Cardoso\CustomerProducts\Api\ProductList
{
    protected ProductRepositoryInterface $productRepository;
    protected Status $productStatus;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected SortOrderBuilder $sortOrderBuilder;
    protected Image $image;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param Status $productStatus
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param Image $image
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Status $productStatus,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        Image $image
    ) {
        $this->productRepository = $productRepository;
        $this->productStatus = $productStatus;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->image = $image;
    }

    public function getProducts(ProductRangeInterface $productRange): array
    {
        $sortOrder = $this->sortOrderBuilder->setField('position')
            ->setDirection($productRange->getSortByPrice())
            ->create();
        $searchCriteria = $this->getCriteria($productRange, $sortOrder);
        $result = $this->productRepository->getList($searchCriteria);
        return $this->getResponse($result->getItems());
    }

    /**
     * @param array $items
     * @return array
     */
    public function getResponse(array $items): array
    {
        $responseData = [];
        foreach ($items as $item) {
            $mageUrl = $this->image->init($item, 'product_thumbnail_image')->getUrl();
            $responseData[] = [
                'sku' => $item->getSku(),
                'price'=> $item->getPrice(),
                'image' => $mageUrl,
                'quantity' => $item->getQty(),
                'description' => $item->getName(),
                'link' => $item->getProductUrl()
            ];
        }

        return $responseData;
    }

    /**
     * @param ProductRangeInterface $productRange
     * @param SortOrder $sortOrder
     * @return SearchCriteria
     */
    public function getCriteria(ProductRangeInterface $productRange, SortOrder $sortOrder): SearchCriteria
    {
        return $this->searchCriteriaBuilder
            ->setPageSize(10)
            ->addFilter('price', $productRange->getLowRange(), 'from')
            ->addFilter('price', $productRange->getHighRange(), 'to')
            ->addFilter(ProductInterface::STATUS, Status::STATUS_ENABLED)
            ->addFilter(ProductInterface::TYPE_ID, Type::TYPE_SIMPLE)
            ->setSortOrders([$sortOrder])
            ->create();
    }
}
