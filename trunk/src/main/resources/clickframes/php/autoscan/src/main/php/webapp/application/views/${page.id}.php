#macro (externalLink $href $text)
<a href="$href">$text</a>
#end

#macro (runtimePageLink $page $text)
<?php echo anchor('${page.id}', '${text}'); ?>
#end

<h2>${page.titleEscaped}</h2>
<!--
	${page.description}
-->

### NAVIGATION
<div id="page-navigation" class="grid_2 alpha">
#if ($page.links.size() > 0)
<ul>
#foreach($linkSet in $page.linkSets)      
#foreach($link in $linkSet.links)
	<li>
#if ($link.internal)
		#runtimePageLink($link)
#else
		#externalLink($link.href $link.title)
#end
	</li>
#end
#end
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
</div>
	  
<div id="page-content" class="grid_10 omega">

	<?php if (is_array($message)) : ?>
	<div class="messages <?php echo $message['class']; ?>"><?php echo $message['text']; ?></div>
	<?php endif; ?>

### CONTENTS
#foreach ($content in $page.contents)
	<div class="content">${content.text}</div>
#end

### OUTPUTS
#if ($page.outputs.size() > 0)
	 <div id="outputs">
#foreach($output in $page.outputs)
		<div class="output grid_5 alpha">
		   <h3>${output.title}</h3>
		   <table>
#foreach ($entityProperty in ${output.entity.properties})
			   <tr>
				  <th>${entityProperty.title}</th>
				  <td>
#if (${entityProperty.type} == 'FILE')
						Download Link
#else
						<?php echo $${output.id}->${entityProperty.id}; ?>
#end
				  </td>
			   </tr>
#end
			   </table>
			</div>
#end ### FOREACH OUTPUT
			<div class="clear"></div>
		 </div>
#end ### OUTPUTS

### OUTPUT LISTS
#foreach ($outputList in $page.outputLists)
	<div class="output-list">
		<h3>${outputList.title}</h3>
		<table>
		<tr>
#foreach ($property in $outputList.entity.properties)
			<th>${property.title}</th>
#end
		</tr>
		<?php foreach ($${outputList.id} as $${outputList.entity.id}) : ?>
		<tr>
#foreach ($property in $outputList.entity.properties)
			<td><?php echo $${outputList.entity.id}->get${property.name}(); ?></td>
#end
		</tr>
		<?php endforeach; ?>
		</table>
	</div>
#end

### FORMS
#foreach ($form in $page.forms)
	<?php echo form_open('${appspec.id}/${page.id}', array('id'=>'${page.id}-${form.id}')); ?>
	<?php echo form_hidden('clickframesFormId', '${page.id}-${form.id}'); ?>

### FORM INPUTS
#if ($form.inputs.size() > 0)
#foreach($input in $form.inputs)
		 <div class="field">
			 <?php echo form_label('${input.title}', '${input.id}', array('class' => 'grid_2 alpha')); ?>
			 <div class="#if ($input.type == 'radio' || $input.type == 'checkbox') field-checkbox #else field-input #end grid_4">
#parse("clickframes/php/inputs.vm")
			 </div>
			 <div class="field-message grid_4 omega">
				 <div class="message"><span id="${input.id}_message"><?php echo form_error('${input.id}'); ?></span></div>
			 </div>
			 <div class="clear"></div>
		 </div>
#end
#end

### FORM ACTIONS
		<div class="actions alpha prefix_2 grid_4">
#foreach($action in $form.actions)
			<?php echo form_submit(array('name'=>'action:${action.id}', 'id'=>'action:${action.id}'), '${action.title}'); ?>
#end
		</div>
		<div class="clear"></div>

	<?php echo form_close(); ?>

#end

<?php /* clickframes::::clickframes */ ?>