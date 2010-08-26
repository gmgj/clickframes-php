#macro (externalLink $href $text)
<a href="$href">$text</a>
#end
#macro (runtimePageLink $link)
<?php echo anchor('${link.page.id}'$!{context.get($link).queryString}, '${link.titleEscaped}'); ?>
#end 

#foreach ($link in $linkSet.links)
<li class="navigation">
#if ($link.isInternal())
    #runtimePageLink($link)
#else
	#externalLink($link.href, $link.title)
#end
</li>
#end

<?php /* clickframes::::clickframes */ ?>