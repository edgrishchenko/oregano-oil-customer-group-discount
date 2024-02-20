{extends file='parent:frontend/index/index.tpl'}

{block name='frontend_index_shop_navigation'}
    {if $magediaIsPlatinumCustomer}
        <div class="platinum-customer-header">
            {$magediaCustomerSalutation}
            <br>
            {s name="platinumCustomerHeaderMessage" namespace="frontend/customer_group_discount"}{/s}
        </div>
    {/if}

    {$smarty.block.parent}
{/block}
