{extends file='parent:frontend/detail/data.tpl'}

{* Discount price *}
{block name='frontend_detail_data_pseudo_price'}
    {if $sArticle.has_pseudoprice}
        {if $magediaIsPlatinumCustomer}

            {* Discount price content *}
            {block name='frontend_detail_data_platinum_discount_price_message'}
                <span class="content--platinum-discount-message">
                       {s name="platinumCustomerProductDetailDiscountMessage" namespace="frontend/customer_group_discount"}{/s}
                </span>
            {/block}
            {block name='frontend_detail_data_pseudo_price_discount_content'}
                <span class="content--discount platinum">
                    {block name='frontend_detail_data_pseudo_price_discount_before'}
                        {s name="priceDiscountLabel"}{/s}
                    {/block}
                    <span class="price--content">{$sArticle.pseudoprice|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}</span>

                    {block name='frontend_detail_data_pseudo_price_discount_after'}
                        {s name="priceDiscountInfo"}{/s}
                    {/block}

                    {block name='frontend_detail_data_platinum_default_price_message'}
                        <span class="content--platinum-discount-message">
                            {s name="platinumCustomerProductDetailDefaultPriceMessage" namespace="frontend/customer_group_discount"}{/s}
                        </span>
                    {/block}
                </span>
            {/block}
        {else}
            {$smarty.block.parent}
        {/if}
    {/if}
{/block}