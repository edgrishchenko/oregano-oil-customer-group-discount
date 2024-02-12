{extends file='parent:frontend/index/topbar-navigation.tpl'}

{block name='frontend_index_top_bar_nav'}
        <div class="customer-group-discount widget raw block">
            {action module=widgets controller=MagediaCustomerGroupDiscount action=info}
        </div>

    {$smarty.block.parent}
{/block}