<?php declare(strict_types=1);

namespace MagediaCustomerGroupDiscount\Subscriber;

use Enlight\Event\SubscriberInterface;

class Controller implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDirectory;

    /**
     * @var \Enlight_Template_Manager
     */
    private $view;

    /**
     * Controller constructor.
     *
     * @param string $pluginDirectory
     * @param \Enlight_Template_Manager $view
     */
    public function __construct(
        string $pluginDirectory,
        \Enlight_Template_Manager $view
    ) {
        $this->pluginDirectory = $pluginDirectory;
        $this->view = $view;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_MagediaCustomerGroupDiscount' =>
                'onGetControllerPathFrontend',
            'Enlight_Controller_Dispatcher_ControllerPath_Widgets_MagediaCustomerGroupDiscount' =>
                'onGetControllerPathWidgets'
        ];
    }

    public function onGetControllerPathFrontend(\Enlight_Event_EventArgs $args)
    {
        $this->view->addTemplateDir($this->pluginDirectory . '/Resources/views');

        return $this->pluginDirectory . '/Controllers/Frontend/MagediaCustomerGroupDiscount.php';
    }

    public function onGetControllerPathWidgets(\Enlight_Event_EventArgs $args)
    {
        $this->view->addTemplateDir($this->pluginDirectory . '/Resources/views');

        return $this->pluginDirectory . '/Controllers/Widgets/MagediaCustomerGroupDiscount.php';
    }
}