{block name="Magedia_CustomerGroupDiscount_Widget_message"}
    {if $magediaCustomerGroupDiscount}
        <div class="magediaCustomerGroupDiscount widget message">
            {include file="frontend/_includes/messages.tpl" type="info" icon="icon--percent2" content="{$magediaCustomerGroupDiscount}"}
        </div>
    {/if}
{/block}