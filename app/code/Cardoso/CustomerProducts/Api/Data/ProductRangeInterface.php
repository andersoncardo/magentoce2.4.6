<?php
declare(strict_types=1);

namespace Cardoso\CustomerProducts\Api\Data;

interface ProductRangeInterface
{
    /**
     * String constants for property names
     */
    const LOW_RANGE = "low_range";
    const HIGH_RANGE = "high_range";
    const SORT_BY_PRICE = "sort_by_price";

    /**
     * Getter for LowRange.
     *
     * @return float|null
     */
    public function getLowRange(): ?float;

    /**
     * Setter for LowRange.
     *
     * @param float|null $lowRange
     *
     * @return void
     */
    public function setLowRange(?float $lowRange): void;

    /**
     * Getter for HightRange.
     *
     * @return float|null
     */
    public function getHighRange(): ?float;

    /**
     * Setter for HightRange.
     *
     * @param float|null $highRange
     *
     * @return void
     */
    public function setHighRange(?float $highRange): void;

    /**
     * Getter for SortByPrice.
     *
     * @return string|null
     */
    public function getSortByPrice(): ?string;

    /**
     * Setter for SortByPrice.
     *
     * @param string|null $sortByPrice
     *
     * @return void
     */
    public function setSortByPrice(?string $sortByPrice): void;
}
