#macro (externalLink $href $text)
<a href="$href">$text</a>
#end

#macro (runtimePageLink $page $text)
<a href="${page.id}">$text</a>
#end

    <div class="content">

        <?php if (is_array($message)) : ?>
        <div class="messages <?php echo $message['class']; ?>"><?php echo $message['text']; ?></div>
        <?php endif; ?>

        #if ($page.links.size() > 0)
        <ul id="links">
            #foreach($link in $page.links)
            <li>
            #if ($link.internal)
                #runtimePageLink($link.page $link.title)
            #else
                #externalLink($link.href $link.title)
            #end
            </li>
            #end
        </ul>
        #end

        <div class="page-description">
            ${page.description}
        </div>

        #if ($page.forms.size() > 0)
          #set( $form = $page.forms.get(0) )
        <?php echo form_open('${appspec.id}/${page.id}', array('id'=>'form${page.name}')); ?>

            #if ($form.inputs.size() > 0)
            <table class="inputs">
                #foreach($input in $form.inputs)
                <tr>
                    <th><?php echo form_label('${input.title}', '${input.id}'); ?></th>
                    #parse("clickframes/php/inputs.vm")
                </tr>
                #end
            </table>
            #end

            #foreach($action in $form.actions)
            <div class="submit"><?php echo form_submit(array('name'=>'action:${action.id}', 'id'=>'action:${action.id}'), '${action.title}'); ?></div>
            #end

        <?php echo form_close(); ?>
        #end

    </div>