#macro (externalLink $href $text)
<a href="$href">$text</a>
#end

#macro (runtimePageLink $page $text)
<?php echo anchor('${appspec.id}/${page.id}', '${text}'); ?>
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

#foreach ($entityList in $page.entityLists)
	<table class="entityList">
	<tr>
#foreach ($property in $entityList.entity.properties)
		<th>${property.title}</th>
#end
	</tr>
	<?php foreach ($${entityList.id} as $${entityList.entity.id}) : ?>
	<tr>
#foreach ($property in $entityList.entity.properties)
		<td><?php echo $${entityList.entity.id}->get${property.name}(); ?></td>
#end
	</tr>
	<?php endforeach; ?>
	</table>
#end

#foreach ($form in $page.forms)
	<?php echo form_open('${appspec.id}/${page.id}', array('id'=>'${page.id}-${form.id}')); ?>

	<?php echo form_hidden('clickframesFormId', '${page.id}-${form.id}'); ?>

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

#foreach ($action in $page.actions)
	<?php echo form_open('${appspec.id}/${page.id}', array('id'=>'${page.id}-action-${action.id}')); ?>
		<?php echo form_hidden('clickframesFormId', '${page.id}-action-${action.id}'); ?>
		<div class="submit action"><?php echo form_submit(array('name'=>'action:${action.id}', 'id'=>'action:${action.id}'), '${action.title}'); ?></div>
	<?php echo form_close(); ?>
#end

</div>