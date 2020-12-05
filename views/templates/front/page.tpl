{extends file='page.tpl'}

{block name='page_content'}
    {if !empty($faqs)}
        <div id="accordion" class="faqs">
            {foreach $faqs as $index => $faq}
                    <div class="faq">
                        <div class="faq__question collapsed" data-toggle="collapse" data-target="#faq{$index}" aria-expanded="true" aria-controls="faq{$index}">
                            {$index + 1}. {$faq.question} <i class="faq__icon fa fa-chevron-down"></i>
                        </div>

                        <div id="faq{$index}" class="collapse" data-parent="#accordion">
                            <div class="faq__answer">
                                {$faq.answer nofilter}
                            </div>
                        </div>
                    </div>
            {/foreach}
        </div>
    {/if}
{/block}
