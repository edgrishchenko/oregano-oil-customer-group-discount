{extends file='parent:frontend/checkout/ajax_cart.tpl'}

{block name='frontend_checkout_ajax_cart_prices_container_inner'}
    {$smarty.block.parent}

    {action module=widgets controller=MagediaCustomerGroupDiscount action=info template="widgets/magediaCustomerGroupDiscount/message-info.tpl"}
{/block}

{block name='frontend_checkout_ajax_cart'}
    <script type="text/javascript">
        $('.magediaCustomerGroupDiscountDifference').html('{$magediaCustomerGroupDiscountSnippet}');
    </script>
    {$smarty.block.parent}
{/block}