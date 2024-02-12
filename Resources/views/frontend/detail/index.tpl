{extends file='parent:frontend/detail/index.tpl'}

{block name="frontend_detail_index_buy_container_base_info"}
    {$smarty.block.parent}

    {action module=widgets controller=MagediaCustomerGroupDiscount action=info template="widgets/magediaCustomerGroupDiscount/message-info.tpl"}
{/block}