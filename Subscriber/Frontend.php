<?php declare(strict_types=1);

namespace MagediaCustomerGroupDiscount\Subscriber;

use Enlight\Event\SubscriberInterface;
use MagediaCustomerGroupDiscount\Service\CustomerGroupDiscount;

class Frontend implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDirectory;

    /**
     * @var CustomerGroupDiscount
     */
    protected $customerGroupDiscount;

    /**
     * Dispatch constructor.
     *
     * @param string $pluginDirectory
     * @param CustomerGroupDiscount $customerGroupDiscount
     */
    public function __construct(
        string $pluginDirectory,
        CustomerGroupDiscount $customerGroupDiscount
    ) {
        $this->pluginDirectory = $pluginDirectory;
        $this->customerGroupDiscount = $customerGroupDiscount;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatchSecureFrontend',
            'Shopware_Controllers_Frontend_Checkout::ajaxAddArticleCartAction::after' => 'afterAddingArticles'
        ];
    }

    /**
     * @param \Enlight_Hook_HookArgs $args
     */
    public function afterAddingArticles(\Enlight_Hook_HookArgs $args)
    {
        /** @var \Shopware_Controllers_Frontend_Checkout $subject */
        $subject = $args->getSubject();
        $return = $args->getReturn();

        $subject->cartAction();

        $args->setReturn($return);
    }

    /**
     * Function onPostDispatch
     *
     * @param \Enlight_Controller_ActionEventArgs $args
     *
     * @throws \Exception
     */
    public function onPostDispatchSecureFrontend(\Enlight_Controller_ActionEventArgs $args)
    {
        $view = $args->getSubject()->View();

        if ($view->hasTemplate()) {
            $view->addTemplateDir($this->pluginDirectory . '/Resources/views');

            $snippet = $this->customerGroupDiscount->renderSnippet();

            // If ajax_add_article or AjaxCart has been called, load updating-template
            if (in_array($args->getSubject()->Request()->getActionName(), ['ajaxCart', 'ajax_add_article'], true)) {
                $args->getSubject()
                    ->View()
                    ->assign('magediaCustomerGroupDiscountData', $this->customerGroupDiscount->getCustomerGroupDiscountData());

                if (!is_string($snippet)) {
                    return;
                }

                $view->assign('magediaCustomerGroupDiscountSnippet', $snippet);
                $html = '<span class="magediaCustomerGroupDiscountDifference">' . $snippet . '</span>';
                $view->assign('magediaCustomerGroupDiscount', $html);
            }

            if ($this->customerGroupDiscount->isPlatinumCustomer()) {
                $view->assign('magediaIsPlatinumCustomer',true);
                $view->assign('magediaCustomerSalutation', $this->customerGroupDiscount->renderCustomerSalutationSnippet());
            }
        }
    }
}