
<!--<h2 class="page_title"><?php //echo __('パスワード再送');?><</h2><!---->

<?php echo $this->Form->create('User',array('novalidate' => true)); ?>
    <ul id="css3form">
        <li><?php echo __('登録しているメールアドレスを入力して、ボタンをクリックしてください。');?></li>
        <li class="fmt"><?php echo __('メールアドレス');?></li>
        <li>
            <?php echo $this->Form->error('User.mail', null, array('escape' => false)); ?>
            <?php echo $this->Form->input('User.mail', array( 'default'=>'', 'class'=>'fm','label' => false,'div' => false, 'error' => false)); ?>
        </li>
        
        <?php echo $this->Form->submit(__('パスワード再設定'), array('div' => false,'class'=>'btn')); ?>
    
    </ul>
<?php echo $this->Form->end(); ?>