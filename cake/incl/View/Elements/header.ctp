<?php
if($auth['name']==""){
	$username = "未設定";
}else{
	$username = $auth['name'];
}
?>
<header>
	<div id="headmain">
        <h1 id="top"><span class="maintitle"><?php echo __('incl');?></span><?php //echo $this->Html->link($this->Html->image('./def/logo03.png'), array('controller'=>'tops','action'=>'index'), array('class' => '','escape'=>false)); ?></h1>
            
        <nav>
            <ul>
                <hr />
                <li class = "headuserdiv">
                	<?php //echo $this->Html->image('./userimg/no_image.png', array('class' => 'headimg','escape'=>false)); ?>
                    <?php echo $this->Html->link(
					//$this->Html->image('../'.$auth['userimg'], array('class' => 'headimg','id'=>'headimgid','escape'=>false))
					'<div class="headimg" id="headimgid"></div>'
					.'<span class="usertitle">'.__('ユーザー名').'</span><br />'
					.'<div id="headusernameid" class="headusername">'.$username.'</div><br />'
					//.__('<span class = "headli">個人設定</span>')
					,array('controller'=>'users','action'=>'usertop'), array('class' => 'userimgzone', 'target' => '','escape'=>false)); ?>
                </li>
                <hr />
                <li>
                    <?php echo $this->Html->link(__('マイディスプレイ'), array('controller'=>'filetops','action'=>'index'), array('class' => 'headli', 'target' => '')); ?>
                </li>
                <li>
                    <?php echo $this->Html->link(__('プロジェクト'), array('controller'=>'users','action'=>'grouptop'), array('class' => 'headli', 'target' => '')); ?>
                </li>
                <li>
                    <?php echo $this->Html->link(__('利用規約'), array('controller'=>'others','action'=>'agreement'), array('class' => 'headli', 'target' => '')); ?>
                </li>
                <li>
                    <?php echo $this->Html->link(__('ヘルプ'), array('controller'=>'others','action'=>'help'), array('class' => 'headli', 'target' => '')); ?>
                </li>
                <li>
                    <?php echo $this->Html->link(__('ログアウト'), array('controller'=>'auths','action'=>'logout'), array('class' => 'headli', 'target' => '')); ?>
                </li>
            </ul>
        </nav>
        
        <div class="lang">
            <?php if($auth['langid']==2){ ?>
                <?php echo $this->Html->link(__('日本語'), array('controller'=>'ajaxfuncs','action'=>'gchan','jpn'), array('class' => 'langbtn', 'escape' => false)); ?>
                <?php echo $this->Html->link(__('English'), array('controller'=>'ajaxfuncs','action'=>'gchan','eng'), array('class' => 'langbtn langon', 'escape' => false)); ?>
            <?php }else{ ?>
                <?php echo $this->Html->link(__('日本語'), array('controller'=>'ajaxfuncs','action'=>'gchan','jpn'), array('class' => 'langbtn langon', 'escape' => false)); ?>
                <?php echo $this->Html->link(__('English'), array('controller'=>'ajaxfuncs','action'=>'gchan','eng'), array('class' => 'langbtn', 'escape' => false)); ?>
            <?php } ?>
        </div>
        
        <address><?php echo __('&copy;incl All Rights Reserved.');?></address>
    </div>
    <div id="headbar">
    	<div id="hanicon" class="toggle_menu">
            <span class="first"></span>
            <span class="second"></span>
            <span class="third"></span>
        </div>
    		<!--<span class="css-bar"></span>-->
        
    </div>
</header>