
<!--<h2 class="page_title"><?php echo __('新規会員登録');?></h2>-->

<?php echo $this->Form->create('User',array('novalidate' => true)); ?>
    <ul id="css3form">
        <li class="fmt"><?php echo __('メールアドレス');?></li>
        <li>
            <?php echo $this->Form->error('User.mail', null, array('escape' => false)); ?>
            <?php echo $this->Form->input('User.mail', array( 'default'=>'', 'class'=>'fm','label' => false,'div' => false, 'error' => false)); ?>
        </li>
        <li class="fmt"><?php echo __('パスワード');?></li>
        <li>
            <?php echo $this->Form->error('User.password', null, array('escape' => false)); ?>
            <?php echo $this->Form->input('User.password', array('value' => '', 'class'=>'fm','label' => false,'div' => false, 'error' => false)); ?>
        </li>
        
        <p class="regi_kiyaku"><?php echo $this->Html->link(__('利用規約'), array('controller'=>'others','action'=>'agreement')); ?></p>
        <?php echo $this->Form->error('check', null, array('escape' => false)); ?>
        <p class="regi_agree"><?php echo $this->Form->input('check', array( 'default'=>'','type'=>'checkbox','label' => __('規約に同意してユーザ登録をする'),'div' => false, 'error' => false)); ?></p>
        
        <?php echo $this->Form->submit(__('登録する'), array('div' => false,'class'=>'btn')); ?>
    
    </ul>
<?php echo $this->Form->end(); ?>