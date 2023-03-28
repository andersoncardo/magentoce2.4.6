<?php
declare(strict_types=1);

namespace Cardoso\CustomerProducts\Controller\Product;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Sales\Controller\OrderInterface;

class Index extends \Magento\Framework\App\Action\Action implements OrderInterface, HttpGetActionInterface
{
    public function execute() {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

}