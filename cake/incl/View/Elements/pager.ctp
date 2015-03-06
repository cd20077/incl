<div class="pager">
    <div class="paging fbox">
		<?php echo $this->Paginator->prev('≪前へ', array(), null, array('class' => 'prev disabled')); ?>
        <?php
		echo $this->Paginator->numbers(array('separator' => '','tag' => 'span'));  
		?>
		<?php echo $this->Paginator->next('次へ≫', array(), null, array('class' => 'next disabled')); ?>
    </div>
</div>