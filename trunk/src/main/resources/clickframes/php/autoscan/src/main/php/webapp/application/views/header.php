<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta name="clickframesPage" content="<?php echo $pageId; ?>" />
        <title><?php echo $pageTitle; ?> - <?php echo $applicationTitle; ?></title>
        
        <link type="text/css" rel="stylesheet" href="/css/reset-fonts.css" />
        <link type="text/css" rel="stylesheet" href="/css/grid.css" />
        <link type="text/css" rel="stylesheet" href="/css/style.css" />
    </head>
    <body>
    
	<div id="header">
		<div class="container_12">
    
			<h1 class="grid_12"><a href="<?php echo base_url(); ?>"><?php echo $applicationTitle; ?></a></h1>
			<div class="clear"></div>
			<ul id="site-navigation" class="grid_8">
                <?php echo $navigation; ?>
            </ul>

			<div id="identity" class="grid_4">Logged in as ...</div>
			<div class="clear"></div>
		
		</div>
	</div>
    
	<div class="container_12">
		<div class="grid_12">

<?php /* clickframes::::clickframes */ ?>