<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="Keywords" content="" lang="ja" xml:lang="ja" />
    <meta name="Description" content="" lang="ja" xml:lang="ja" />
    
    <title><?php echo __('incl'); ?></title>
    
    <!--  StyleSheet記述  -->
    <?php
    echo $this->Html->css('./style');
    echo $this->fetch('css');
    ?>
    <!--  /StyleSheet記述  -->
    
    <!--  javascript記述  -->
    <?php
    echo $this->Html->script('./jquery-1.11.0.min');
    //echo $this->Html->script('./jquery.min');
    echo $this->Html->script('./jquery.cookie');
    echo $this->Html->script('./main2');
    //echo $this->Html->script('./respond.min');
    
    echo $this->fetch('script');
    ?>
    <!--  /javascript記述  -->
    <?php
    echo $this->html->meta('icon', '/favicon.ico?ver=0001');
	?>
    
</head>
<body>
    
    <?php echo $this->Session->flash(); ?>
    
	<?php echo $this->fetch('content'); ?>

</body>
</html>

		 

		
		
		
