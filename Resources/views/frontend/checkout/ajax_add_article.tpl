{extends file="parent:frontend/checkout/ajax_add_article.tpl"}

{block name='checkout_ajax_add_title'}
    <script type="text/javascript">
        $('.magediaCustomerGroupDiscountDifference').html('{$magediaCustomerGroupDiscountSnippet}');
    </script>
    {$smarty.block.parent}
{/block}
