{extends file='parent:frontend/checkout/cart.tpl'}

{block name='frontend_index_content'}
    {if $magediaIsPlatinumCustomer}
        <div class="platinum-customer-cart">
            <p>{$magediaCustomerSalutation},</p>

            <p>{s name="platinumCustomerCartMainMessage" namespace="frontend/customer_group_discount"}{/s},
                {$magediaCustomerSalutation} {s name="platinumCustomerCartMainInclude" namespace="frontend/customer_group_discount"}{/s}</p>

            <p>{s name="platinumCustomerCartMainGratitude" namespace="frontend/customer_group_discount"}{/s},</p>

            <p>Michail Raptis<br>
                {s name="platinumCustomerCartMainPosition" namespace="frontend/customer_group_discount"}{/s}<br>
                Athina® {s name="platinumCustomerCartMainCompany" namespace="frontend/customer_group_discount"}{/s}
            </p>
        </div>
    {/if}

    {$smarty.block.parent}
{/block}