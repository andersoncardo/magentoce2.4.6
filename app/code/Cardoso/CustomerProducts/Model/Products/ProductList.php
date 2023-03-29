<?php
declare(strict_types=1);

namespace Cardoso\CustomerProducts\Model\Products;

use Cardoso\CustomerProducts\Api\Data\ProductRangeInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Helper\Data;

class ProductList implements \Cardoso\CustomerProducts\Api\ProductList
{
    private ProductRepositoryInterface $productRepository;
    private Status $productStatus;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private SortOrderBuilder $sortOrderBuilder;
    private Image $image;
    private StockItemRepository $stockItemRepository;
    private \Magento\Framework\Pricing\Helper\Data $price;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param Status $productStatus
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param Image $image
     * @param StockItemRepository $stockItemRepository
     * @param Data $price
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Status $productStatus,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        Image $image,
        StockItemRepository $stockItemRepository,
        \Magento\Framework\Pricing\Helper\Data $price
    ) {
        $this->productRepository = $productRepository;
        $this->productStatus = $productStatus;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->image = $image;
        $this->stockItemRepository = $stockItemRepository;
        $this->price = $price;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getProducts(ProductRangeInterface $productRange): array
    {
        $sortOrder = $this->sortOrderBuilder->setField('price')
            ->setDirection($productRange->getSortByPrice())
            ->create();
        $searchCriteria = $this->getCriteria($productRange, $sortOrder);
        $result = $this->productRepository->getList($searchCriteria);
        return $this->getResponse($result->getItems());
    }

    /**
     * @param array $items
     * @return array
     * @throws NoSuchEntityException
     */
    public function getResponse(array $items): array
    {
        $responseData = [];
        foreach ($items as $item) {
            $mageUrl = $this->image->init($item, 'product_thumbnail_image')->getUrl();
            $stock = $this->stockItemRepository->get($item->getId());
            $responseData[] = [
                'sku' => $item->getSku(),
                'price'=> $this->price->currency($item->getPrice(), true, false),
                'image' => $mageUrl,
                'quantity' => $stock->getQty(),
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
