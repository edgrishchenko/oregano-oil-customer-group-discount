<?php declare(strict_types=1);

class Shopware_Controllers_Frontend_MagediaCustomerGroupDiscount extends Enlight_Controller_Action
{
    /**
     * Default action of the frontend controller
     */
    public function indexAction()
    {
        Shopware()->Plugins()->Controller()->ViewRenderer()->setNoRender();

        echo json_encode(
            [
                'success' => true,
                'snippet' => Shopware()->Container()->get('magedia_customer_group_discount.customer_group_discount_service')->renderSnippet()
            ]
        );
    }
}
