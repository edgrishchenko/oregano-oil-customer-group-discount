<?php declare(strict_types=1);

namespace MagediaCustomerGroupDiscount\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use Shopware\Components\Theme\LessDefinition;

class TemplateRegistration implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDirectory;

    /**
     * @var \Enlight_Template_Manager
     */
    private $templateManager;

    /**
     * @param string $pluginDirectory
     * @param \Enlight_Template_Manager $templateManager
     */
    public function __construct(
        string $pluginDirectory,
        \Enlight_Template_Manager $templateManager
    ) {
        $this->pluginDirectory = $pluginDirectory;
        $this->templateManager = $templateManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'onPreDispatch',
            'Theme_Compiler_Collect_Plugin_Less' => 'onCollectLess'
        ];
    }

    public function onPreDispatch()
    {
        $this->templateManager->addTemplateDir($this->pluginDirectory . '/Resources/views');
    }

    /**
     * @param Enlight_Event_EventArgs $args
     *
     * @return LessDefinition
     */
    public function onCollectLess(Enlight_Event_EventArgs $args)
    {
        return new LessDefinition(
            [],
            [
                $this->pluginDirectory . '/Resources/views/frontend/_public/src/less/style.less',
            ],
            $this->pluginDirectory
        );
    }
}
