
<?php
    $this->Html->scriptStart(array('inline' => false));
    echo
<<<END
        top.location.href='../Users/usertop';
END;
    $this->Html->scriptEnd();
?>
<?php //echo $this->Html->image('loading3.gif',array('class'=>'loadimg')); ?>
<div id="back">
</div>