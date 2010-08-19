#set($dollarSign="$")
#macro (externalLink $href $text)
<a href="$href">$text</a>
#end
#macro (runtimePageLink $link)
<?php echo anchor('${link.page.id}'$!{context.get($link).queryString}, '${link.titleEscaped}'); ?>
#end 

<h2>${page.titleEscaped}</h2>
<!--
	${page.description}
-->

### NAVIGATION
<div id="page-navigation" class="grid_2 alpha">
#if ($page.links.size() > 0 || $page.linkSets.size() > 0)
<ul>
#foreach($link in $page.links)
	<li>
#if ($link.internal)
		#runtimePageLink($link)
#else
		#externalLink($link.href $link.title)
#end
	</li>
#end
#foreach($linkSetId in $page.linkSetIds)
<?php include('navigation/${linkSetId}.php'); ?>
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
#if ($content.verbatim)
	<div class="content verbatim" id="content_${content.id}"><?php echo $this->lang->line('${appspec.id}_${page.id}_${content.id}'); ?></div>
#else
	<div class="content nonverbatim" id="content_${content.id}"><?php include('content/${page.id}_${content.id}.php'); ?></div>
#end
#end

### OUTPUTS
#if ($page.outputs.size() > 0)
	<div id="outputs">
#foreach($output in $page.outputs)
	<?php if (isset(${dollarSign}outputs['${output.id}'])) : ?>
		<div class="output grid_5 alpha">
			<h3>${output.title}</h3>
			<table>
#foreach ($entityProperty in ${output.entity.properties})
				<tr>
					<th>${entityProperty.title}</th>
					<td>
#if ($entityProperty.multiple)
						<?php echo count(${dollarSign}outputs['${output.id}']->get${entityProperty.name}()); ?>
#elseif ($entityProperty.type == 'FILE')
						Download Link
#else
						<?php echo ${dollarSign}outputs['${output.id}']->get${entityProperty.name}(); ?>
#end
					</td>
				</tr>
#end
			</table>
		</div>
	<?php endif; ?>
#end ### FOREACH OUTPUT
	<div class="clear"></div>
	</div>
#end ### OUTPUTS

### OUTPUT LISTS
#foreach ($outputList in $page.outputLists)
	<div class="output-list">
		<h3>${outputList.title}</h3>
		<?php if (isset(${dollarSign}outputLists['${outputList.id}']) && sizeof($outputLists['${outputList.id}']) > 0) : ?>
		<table>
		<tr>
#if ($outputList.links.size() > 0 || $outputList.actions.size() > 0)
			<th></th>
#end
#foreach ($property in $outputList.entity.properties)
			<th>${property.title}</th>
#end
		</tr>
		<?php foreach (${dollarSign}outputLists['${outputList.id}'] as $${outputList.entity.id}) : ?>
		<tr>
#if ($outputList.links.size() > 0 || $outputList.actions.size() > 0)
			<td>
#foreach ($link in $outputList.links)
#if ($link.internal)
				#runtimePageLink($link)
#else
				#externalLink($link.href $link.title)
#end
#end
#foreach ($action in $outputList.actions)
				<?php echo anchor('${page.id}/${action.id}/' . $uriSegments . '/' . $${outputList.entity.id}->get${outputList.entity.primaryKey.name}(), '${action.title}'); ?>
#end
			</td>
#end
#foreach ($property in $outputList.entity.properties)
			<td><?php echo $${outputList.entity.id}->get${property.name}(); ?></td>
#end
		</tr>
		<?php endforeach; ?>
		</table>
		<?php else : ?>
		<p class="notification">No items found.</p>
		<?php endif; ?>
	</div>
#end

### FORMS
#foreach ($form in $page.forms)
	<?php echo form_open(uri_string(), array('id'=>'${page.id}-${form.id}', 'class'=>'validate')); ?>
	<?php echo form_hidden('clickframesFormId', '${page.id}-${form.id}'); ?>
#foreach ($entity in $form.entities)
	<?php if (isset($outputs['${entity.id}'])) { echo form_hidden('${entity.id}_${entity.primaryKey.id}', $outputs['${entity.id}']->get${entity.primaryKey.name}()); } ?>
#end

### FORM INPUTS
#if ($form.inputs.size() > 0)
#foreach($input in $form.inputs)
		 <div class="field">
			 <?php echo form_label('${input.title}', '${input.id}', array('class' => 'grid_2 alpha')); ?>
			 <div class="#if ($input.type == 'radio' || $input.type == 'checkbox') field-checkbox #else field-input #end grid_4">
#parse("clickframes/php/inputs.vm")
			 </div>
			 <div class="field-message grid_4 omega">
				 <div id="${input.id}_message"><?php echo $this->formvalidation->error('${input.id}'); ?></div>
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
	<script type="text/javascript" src="<?php echo base_url(); ?>js/forms/${page.id}_${form.id}.js"></script>
	
#end

<?php /* clickframes::::clickframes */ ?>