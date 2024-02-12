<?php declare(strict_types=1);

namespace MagediaCustomerGroupDiscount;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;

class MagediaCustomerGroupDiscount extends Plugin
{
    /**
     * This method can be overridden
     *
     * @param UpdateContext $context
     */
    public function update(UpdateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }

    /**
     * This method can be overridden
     *
     * @param ActivateContext $context
     */
    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }

    /**
     * This method can be overridden
     *
     * @param DeactivateContext $context
     */
    public function deactivate(DeactivateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }

    /**
     * This method can be overridden
     *
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }
}