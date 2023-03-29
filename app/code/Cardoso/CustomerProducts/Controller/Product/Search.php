<?php
declare(strict_types=1);

namespace Cardoso\CustomerProducts\Controller\Product;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Serialize\Serializer\Json;

class Search implements HttpPostActionInterface
{
    private Json $jsonHelper;
    private JsonFactory $resultJsonFactory;
    private Context $context;
    private \Cardoso\CustomerProducts\Model\Products\Results $productSearchResults;

    /**
     * @param Json $jsonHelper
     * @param JsonFactory $resultJsonFactory
     * @param Context $context
     */
    public function __construct(
        Json $jsonHelper,
        JsonFactory $resultJsonFactory,
        Context $context,
        \Cardoso\CustomerProducts\Model\Products\Results $results
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->context = $context;
        $this->productSearchResults = $results;
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
        if (!$this->validateRequest($data)) {
            return $resultJson->setJsonData(
                $this->jsonHelper->serialize($responseData)
            );
        }

        try {
            $results = $this->productSearchResults->getProducts($data);
            $responseData['message'] =   __('email has been subscribed successfully');
            $responseData['body'] = $results;
        } catch (\Exception $e) {
            $responseData['message'] =   __('error:' . $e->getMessage());
            $responseData['body'] = [];
        }

        return $resultJson->setJsonData(
            $this->jsonHelper->serialize($responseData)
        );

    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function validateRequest(Object $data): bool
    {
        if (!property_exists($data, 'low-range')
            || !property_exists($data, 'high-range')
            || !property_exists($data, 'sort-by-price')) {
            return false;
        }

        return true;
    }
}
