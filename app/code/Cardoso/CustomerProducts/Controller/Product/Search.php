<?php
declare(strict_types=1);

namespace Cardoso\CustomerProducts\Controller\Product;

use Cardoso\CustomerProducts\Api\Data\ProductRangeInterface;
use Cardoso\CustomerProducts\Api\Data\ProductRangeInterfaceFactory;
use Cardoso\CustomerProducts\Model\Products\ProductList;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Serialize\Serializer\Json;

class Search implements HttpPostActionInterface
{
    private Json $jsonHelper;
    private JsonFactory $resultJsonFactory;
    private Context $context;
    private ProductList $productSearchResults;
    private ProductRangeInterfaceFactory $productRangeInterfaceFactory;

    /**
     * @param Json $jsonHelper
     * @param JsonFactory $resultJsonFactory
     * @param Context $context
     * @param ProductList $results
     * @param ProductRangeInterfaceFactory $productRangeInterfaceFactory
     */
    public function __construct(
        Json                         $jsonHelper,
        JsonFactory                  $resultJsonFactory,
        Context                      $context,
        ProductList                  $results,
        ProductRangeInterfaceFactory $productRangeInterfaceFactory
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->context = $context;
        $this->productSearchResults = $results;
        $this->productRangeInterfaceFactory = $productRangeInterfaceFactory;
    }

    public function execute()
    {
        $responseData = [];
        $content = $this->context->getRequest()->getContent();
        $resultJson = $this->resultJsonFactory->create();
        if (!$content) {
            return $resultJson->setJsonData(
                $this->jsonHelper->serialize($responseData)
            );
        }

        $data = json_decode($content);
        $request =  $this->validateRequest($data);
        if (empty($request->getData())) {
            return $resultJson->setJsonData(
                $this->jsonHelper->serialize($responseData)
            );
        }

        try {
            $results = $this->productSearchResults->getProducts($request);
            $responseData = $results;
        } catch (\Exception $e) {
        }

        return $resultJson->setJsonData(
            $this->jsonHelper->serialize($responseData)
        );
    }

    /**
     * @param mixed $data
     * @return ProductRangeInterface
     */
    public function validateRequest(Object $data): ProductRangeInterface
    {
        $productRange = $this->productRangeInterfaceFactory->create();
        if (!property_exists($data, 'lowRange')
            || !property_exists($data, 'highRange')
            || !property_exists($data, 'sortByPrice')) {
            return $productRange;
        }

        $productRange = $this->productRangeInterfaceFactory->create();
        $productRange->setLowRange((float)$data->lowRange);
        $productRange->setHighRange((float)$data->highRange);
        $productRange->setSortByPrice($data->sortByPrice);
        return $productRange;
    }
}
