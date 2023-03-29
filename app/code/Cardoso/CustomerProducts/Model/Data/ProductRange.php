<?php
declare(strict_types=1);

namespace Cardoso\CustomerProducts\Model\Data;

use Cardoso\CustomerProducts\Api\Data\ProductRangeInterface;
use Magento\Framework\DataObject;

class ProductRange extends DataObject implements ProductRangeInterface
{
    /**
     * Getter for LowRange.
     *
     * @return float|null
     */
    public function getLowRange(): ?float
    {
        return $this->getData(self::LOW_RANGE) === null ? null
            : (float)$this->getData(self::LOW_RANGE);
    }

    /**
     * Setter for LowRange.
     *
     * @param float|null $lowRange
     *
     * @return void
     */
    public function setLowRange(?float $lowRange): void
    {
        $this->setData(self::LOW_RANGE, $lowRange);
    }

    /**
     * Getter for HightRange.
     *
     * @return float|null
     */
    public function getHighRange(): ?float
    {
        return $this->getData(self::HIGH_RANGE) === null ? null
            : (float)$this->getData(self::HIGH_RANGE);
    }

    /**
     * Setter for HightRange.
     *
     * @param float|null $highRange
     *
     * @return void
     */
    public function setHighRange(?float $highRange): void
    {
        $this->setData(self::HIGH_RANGE, $highRange);
    }

    /**
     * Getter for SortByPrice.
     *
     * @return string|null
     */
    public function getSortByPrice(): ?string
    {
        return $this->getData(self::SORT_BY_PRICE);
    }

    /**
     * Setter for SortByPrice.
     *
     * @param string|null $sortByPrice
     *
     * @return void
     */
    public function setSortByPrice(?string $sortByPrice): void
    {
        $this->setData(self::SORT_BY_PRICE, $sortByPrice);
    }
}
