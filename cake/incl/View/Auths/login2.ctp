<div id="container">
    
    <!-- ▼メインここから -->
    <main>
    
        <h2 class="page_title"><?php echo __('ログイン');?></h2>
        
        <?php echo $this->Form->create('Auth'); ?>
            <ul id="css3form">
                <li class="fmt"><?php echo __('メールアドレス');?></li>
                <li>
                    <?php echo $this->Form->input('Auth.login_mail', array( 'default'=>'', 'class'=>'fm','label' => false,'div' => false, 'required' => false)); ?>
                </li>
                <li class="fmt"><?php echo __('パスワード');?></li>
                <li>
                    <?php echo $this->Form->input('Auth.login_pw', array('type' => 'password','value' => '', 'class'=>'fm','label' => false,'div' => false, 'required' => false)); ?>
                <?php echo $this->Session->flash(); ?>
                </li>
                <?php echo $this->Form->submit(__('ログインする'), array('div' => false,'class'=>'btn')); ?>
                <p class="login_forget"> <?php echo $this->Html->link(__('パスワードを忘れた方はこちら'), array('controller'=>'Users','action'=>'pass_reset'), array('class' => '','escape'=>false)); ?></p>
            </ul>
        <?php echo $this->Form->end(); ?>
    
    </main>
    <!-- ▲メインここまで -->
	
    <header>
        <div id="headmain">
            <h1 id="top">
            	<span class="maintitle"><?php echo __('incl');?></span>
            </h1>
                
        </div>
    </header>
<!-- / #wrap --></div>
