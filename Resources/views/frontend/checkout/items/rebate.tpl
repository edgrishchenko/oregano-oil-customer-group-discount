{extends file='parent:frontend/checkout/items/rebate.tpl'}

{block name='frontend_checkout_cart_item_rebate_name_wrapper'}
    {if $magediaIsPlatinumCustomer}
        <div class="table--tr block-group row--platinum-customer-cart">
            <div class="table--column column--message">
                <div class="panel--td table--content">
                    {s name="platinumCustomerCartAdditionalMessage" namespace="frontend/customer_group_discount"}{/s}
                </div>
            </div>
        </div>
    {/if}

    {$smarty.block.parent}
{/block}