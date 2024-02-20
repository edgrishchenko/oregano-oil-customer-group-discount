<?php declare(strict_types=1);

namespace MagediaCustomerGroupDiscount\Service;

use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Models\Customer\Customer;

class CustomerGroupDiscount
{
    private $sBasketAmount;

    private $discountLimitDifference;

    private $discountPercentage;

    private $discountStep;

    private $lastStep = false;

    /**
     * @var string
     */
    private $pluginName;

    /** @var ConfigReader */
    private $config;

    /**
     * @var ContextServiceInterface
     */
    private $contextInterface;

    /**
     * @var \Enlight_Components_Db_Adapter_Pdo_Mysql
     */
    private $db;

    /** @var \Enlight_Components_Session_Namespace */
    private $session;

    /**
     * @var \Shopware_Components_Snippet_Manager
     */
    private $snippets;

    /**
     * @var \Enlight_Template_Manager
     */
    private $template;

    /**
     * @var ModelManager
     */
    private $modelManager;

    /**
     * @param string $pluginName
     * @param ConfigReader $config
     * @param ContextServiceInterface $contextInterface
     * @param \Enlight_Components_Db_Adapter_Pdo_Mysql $db
     * @param \Enlight_Components_Session_Namespace $session
     * @param \Shopware_Components_Snippet_Manager $snippets
     * @param \Enlight_Template_Manager $template
     */
    public function __construct(
        string $pluginName,
        ConfigReader $config,
        ContextServiceInterface $contextInterface,
        \Enlight_Components_Db_Adapter_Pdo_Mysql $db,
        \Enlight_Components_Session_Namespace $session,
        \Shopware_Components_Snippet_Manager $snippets,
        \Enlight_Template_Manager $template,
        ModelManager $modelManager
    ) {
        $this->pluginName = $pluginName;
        $this->config = $config;
        $this->contextInterface = $contextInterface;
        $this->db = $db;
        $this->session = $session;
        $this->snippets = $snippets;
        $this->template = $template;
        $this->modelManager = $modelManager;
    }

    public function getCustomerGroupDiscountData()
    {
        return [
            'discountLimitDifference' => $this->discountLimitDifference,
            'discountPercentage' => $this->discountPercentage
        ];
    }

    /**
     * @return string|bool
     * @throws \Exception
     */
    public function renderSnippet()
    {
        $this->discountLimitDifference = $this->discountLimitDifference ?: $this->calculateDiscountLimitDifference();

        if ($this->contextInterface->getShopContext()->getCurrentCustomerGroup()->getId() !==
            $this->config->getByPluginName($this->pluginName)['magediaSelectedCustomerGroup'] || $this->discountLimitDifference === false) {
            return false;
        }

        $snippetNamespace = $this->snippets->getNamespace('frontend/customer_group_discount');

        $this->discountPercentage = $this->discountStep['discountPercentage'];

        $this->template->assign('magediaDiscountLimitDifference', $this->discountLimitDifference);
        $this->template->assign('magediaDiscountPercentage', $this->discountPercentage);
        $discountLimitDifference = $this->template->fetch('string:{$magediaDiscountLimitDifference|currency}');
        $discountPercentage  = $this->template->fetch('string:{$magediaDiscountPercentage}');
        $snippet = $this->lastStep
            ? $snippetNamespace->get('customer_group_discount_last_step')
            : $snippetNamespace->get('customer_group_discount_step');

        $snippet = str_replace('%DISCOUNTLIMIT%', $discountLimitDifference, $snippet);
        $snippet = str_replace('%DISCOUNTPERCENTAGE%', $discountPercentage, $snippet);

        return $this->template->fetch('string:'. $snippet);
    }

    /**
     * Function to calculate the DiscountLimitDifference
     *
     * @return float|bool
     */
    protected function calculateDiscountLimitDifference()
    {
        // get basket amount
        $this->sBasketAmount = $this->sBasketAmount ?: $this->getBasketAmount();

        $customerGroupDiscounts = $this->getCustomerGroupDiscounts();
        if (!$customerGroupDiscounts) {
            return false;
        }

        $discountLimitDifference = 0;
        foreach ($customerGroupDiscounts as $key => $step) {
            if ($this->sBasketAmount <= (float) $step['basketdiscountstart']) {
                $discountLimitDifference = (float) $step['basketdiscountstart'] - $this->sBasketAmount;

                $this->discountStep = [
                    'discountPercentage' => (float) $step['basketdiscount']
                ];

                break;
            } else {
                if (!$customerGroupDiscounts[$key + 1]) {
                    $this->lastStep = true;
                }

                $this->discountStep = [
                    'discountPercentage' => (float) $step['basketdiscount']
                ];
            }
        }

        $this->discountLimitDifference = (float) $discountLimitDifference;

        return $this->discountLimitDifference;
    }

    /**
     * get the total amount of the basket
     *
     * @return int|float|string basket amount
     */
    public function getBasketAmount()
    {
        $basket = $this->getBasket();

        $factor = $this->contextInterface->getShopContext()->getCurrency()->getFactor();
        if (empty($basket)) {
            return 0;
        } else {
            $sBasketAmount = ((float) $basket['AmountWithTaxNumeric']) / $factor;
        }

        // it's weird solution, but can't get totalAmount from Shopware()->Modules()->Basket();
        $basketDiscount = $this->db->fetchOne(
            "SELECT price FROM s_order_basket WHERE sessionID=? AND (modus = 3 OR modus = 4)",
            [$this->session->sessionId]
        );

        if ($basketDiscount) {
            $sBasketAmount = $sBasketAmount - $basketDiscount;
        }

        return $sBasketAmount;
    }

    /**
     * @return array
     */
    protected function getBasket()
    {
        $basket = array(
            'AmountNumeric'        => 0,
            'AmountWithTaxNumeric' => 0,
            'content'              => array()
        );

        $sql = 'SELECT price, netprice, currencyFactor, quantity, modus FROM s_order_basket WHERE sessionID = ?';

        $basketData = $this->db->fetchAll($sql, array($this->session->sessionId));

        foreach ($basketData as $item) {
            $factor = $item['currencyFactor'] ?: 1;
            $basket['AmountNumeric'] += $item['netprice'] * $factor * $item['quantity'];
            $basket['AmountWithTaxNumeric'] += $item['price'] * $factor * $item['quantity'];
            $basket['content'][] = $item;
        }

        return $basket;
    }

    /**
     * @return array|false
     */
    public function getCustomerGroupDiscounts()
    {
        $discounts = $this->db->fetchAll(
            'SELECT basketdiscount, basketdiscountstart
                FROM s_core_customergroups_discounts
                WHERE groupID = ?
                ORDER BY basketdiscountstart ASC',
            [$this->contextInterface->getShopContext()->getCurrentCustomerGroup()->getId()]
        );

        return $discounts;
    }

    /**
     * @return bool
     */
    public function isPlatinumCustomer()
    {
        if ($this->contextInterface->getShopContext()->getCurrentCustomerGroup()->getId() !==
            $this->config->getByPluginName($this->pluginName)['magediaSelectedCustomerGroup']) {

            return false;
        }

        return true;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function renderCustomerSalutationSnippet()
    {
        $snippetNamespace = $this->snippets->getNamespace('frontend/customer_group_discount');

        $userId = $this->session->get('sUserId');
        $customer = $this->modelManager->find(Customer::class, $userId);

        $snippet = $customer->getSalutation() == 'mr'
            ? $snippetNamespace->get('customer_group_discount_salutation_mr')
            : $snippetNamespace->get('customer_group_discount_salutation_ms');

        $snippet = str_replace('%FIRSTNAME%', $customer->getFirstname(), $snippet);
        $snippet = str_replace('%LASTNAME%', $customer->getLastname(), $snippet);

        return $this->template->fetch('string:'. $snippet);
    }
}