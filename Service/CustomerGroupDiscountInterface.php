<?php declare(strict_types=1);

namespace MagediaCustomerGroupDiscount\Service;

interface CustomerGroupDiscountInterface
{
    /**
     * @return array
     */
    public function getCustomerGroupDiscountData();

    /**
     * @return string|bool
     */
    public function renderSnippet();

    /**
     * get the total amount of the basket
     *
     * @return int|float|string basket amount
     */
    public function getBasketAmount();
}