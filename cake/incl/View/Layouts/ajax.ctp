
<!--  StyleSheet記述  -->
<?php
echo $this->Html->css('./style');
echo $this->Html->css('./form');
echo $this->fetch('css');
?>
<!--  /StyleSheet記述  -->

<!--  javascript記述  -->
<?php
echo $this->fetch('script');
?>
<?php echo $this->fetch('content'); ?>
    
<?php echo $this->Session->flash(); ?>
