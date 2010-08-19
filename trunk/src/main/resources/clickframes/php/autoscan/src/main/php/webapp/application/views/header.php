#set($dollarSign="$")
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta name="clickframesPage" content="<?php echo $pageId; ?>" />
        <title><?php echo $pageTitle; ?> - <?php echo $applicationTitle; ?></title>
        
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>css/reset-fonts.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>css/grid.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" />
		
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
		
		<script type="text/javascript">
			${dollarSign}(document).ready(function(){
				${dollarSign}.validator.setDefaults({
					errorPlacement: function(error, element) {
						error.appendTo(${dollarSign}("#" + element.attr("id") + "_message"));
					},
					errorElement: "span"
				});
			});
		</script>
		
    </head>
    <body>
    
	<div id="header">
		<div class="container_12">
    
			<h1 class="grid_12"><a href="<?php echo base_url(); ?>"><?php echo $applicationTitle; ?></a></h1>
			<div class="clear"></div>
			<ul id="site-navigation" class="grid_8">
				<?php if (isset($navigations)) { foreach ($navigations as $navigation) { echo $navigation; } } ?>
            </ul>

			<div id="identity" class="grid_4">
				<?php if ($this->session->userdata('username') !== FALSE) : ?>
				Logged in as <strong><?php echo $this->session->userdata('username'); ?></strong> &middot; <?php echo anchor('${appspec.loginPage.id}/logout', 'Log out'); ?>
				<?php else : ?>
				<?php echo anchor('${appspec.loginPage.id}', 'Log in'); ?>
				<?php endif; ?>
			</div>
			<div class="clear"></div>
		
		</div>
	</div>
    
	<div class="container_12">
		<div class="grid_12">

<?php /* clickframes::::clickframes */ ?>