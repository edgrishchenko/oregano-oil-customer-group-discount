<?php declare(strict_types=1);

class Shopware_Controllers_Widgets_MagediaCustomerGroupDiscount extends Enlight_Controller_Action
{
    public function infoAction()
    {
        $template = $this->Request()->getParam('template');

        if (!$template || 'ajax' === $template) {
            $template = 'widgets/magediaCustomerGroupDiscount/raw.tpl';
        }

        $this->View()->loadTemplate($template);

        $customerGroupDiscountService = Shopware()->Container()->get('magedia_customer_group_discount.customer_group_discount_service');
        $snippet = $customerGroupDiscountService->renderSnippet();

        //Raw data variables to be used in custom formatted messages
        $this->View()->assign('magediaCustomerGroupDiscountData', $customerGroupDiscountService->getCustomerGroupDiscountData());

        $html = $snippet ? '<span class="magediaCustomerGroupDiscountDifference">' . $snippet . '</span>' : null;
        $this->View()->assign("magediaCustomerGroupDiscount", $html);
    }
}