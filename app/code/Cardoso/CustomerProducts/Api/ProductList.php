<?php

namespace Cardoso\CustomerProducts\Api;

interface ProductList
{
    /**
     * @param Data\ProductRangeInterface $productRange
     * @return array
     */
    public function getProducts(\Cardoso\CustomerProducts\Api\Data\ProductRangeInterface $productRange): array;

}