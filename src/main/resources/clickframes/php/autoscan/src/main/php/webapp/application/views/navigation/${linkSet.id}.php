#macro (externalLink $href $text)
<a href="$href">$text</a>
#end

#macro (runtimePageLink $page $text)
<a href="<?php echo site_url('${page.id}'); ?>">$text</a>
#end

#foreach ($link in $linkSet.links)
<li>
    #if ($link.isInternal())
        #runtimePageLink($link.page, $link.title)
    #else
        #externalLink($link.href, $link.title)
    #end
</li>
#end

<?php /* clickframes::::clickframes */ ?>