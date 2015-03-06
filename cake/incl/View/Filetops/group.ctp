
<?php echo $this->Html->script('jquery-ui.min', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery.colorbox-min', array('inline' => false)); ?>
<?php if($auth['langid']==2){ ?>
    <?php echo $this->Html->script('dnd2eng', array('inline' => false)); ?>
<?php }else{ ?>
    <?php echo $this->Html->script('dnd2', array('inline' => false)); ?>
<?php } ?>
<?php echo $this->Html->script('jquery.halocontext', array('inline' => false)); ?>
<?php echo $this->Html->css( 'colorbox', array('inline' => false)); ?>
<?php echo $this->Html->css( 'jquery-ui.min', array('inline' => false)); ?>
<?php echo $this->Html->css( 'form', array('inline' => false)); ?>
<?php echo $this->Html->css( 'rclick', array('inline' => false)); ?> 
<?php echo $this->Html->css( 'chat', array('inline' => false)); ?>

<div id="container">
    
    <!-- ▼メインここから -->
    <main>
        <article id="dropzone" class="sortable-list">
        	<input type="hidden" id="proid" value="<?php echo $groupMembers['GroupList']['ranid']; ?>">
        	<div id="gmenu">
            	<ul id="nav4"> 
                    <li class="b3"><?php echo $this->Html->link(__('設定'), array('controller' => 'GroupMembers', 'action' => 'groupdetail',$groupMembers['GroupList']['ranid']), array( 'escape'=>false)); ?></li> 
                    <li class="b2"><a href="#inline_chat" class="chatopen"><?php echo __('チャット');?></a></li> 
                </ul> 
            </div>
    		<?php echo $this->Session->flash(); ?>
    		<?php //print_r($dir); ?>
            
            <?php $i=0; ?>
			<?php foreach ($dir as $dir1): ?>
            	<?php if($i == 0){ ?>
					<?php foreach ($dir1 as $dir2): ?>
                        <?php //if($i != 0){ ?>
                        <div id="<?php echo h($dir2); ?>" data-dirname="" data-fname="<?php echo h($dir2); ?>" class="folderdiv outfdiv">
                            <span class="folderd"><p><?php echo h($dir2); ?></p></span>
                        </div>
                    <?php endforeach; ?>
                <?php }else{ ?>
					<?php foreach ($dir1 as $dir2): ?>
                        <?php //if($i != 0){ ?>
                        <div id="<?php echo h($dir2); ?>" data-dirname="" data-fname="<?php echo h($dir2); ?>" class="filediv outfdiv">
                            <span class="file"><p><?php echo h($dir2); ?></p></span>
                        </div>
                    <?php endforeach; ?>
                <?php } ?>
            	<?php $i++; ?>
            <?php endforeach; ?>
            <!-- ▼フッターここから -->
            <?php echo $this->element('footer')?>
            <!-- ▲フッターここまで -->
        </article>
    
    </main>
    <!-- ▲メインここまで -->
    
    <!-- ▼チャットここから -->
    <div style='display:none'>
        <div id='inline_chat'>
            <div id="containar2">
                <div id="Log">
                    <ul></ul>
                </div>
            </div>

            <div id="chat">
                <input type="text" name="str" id="str" class="inputtext">
                <input type="button" name="button1" id="button1" class="searchbtn" value="<?php echo __('送信');?>">
            </div>
        </div>
    </div>
    <!-- ▲チャットここまで -->
	
    <!-- ▼ヘッダーここから -->
    <?php echo $this->element('header')?>
    <!-- ▲ヘッダーここまで -->

<!-- / #container --></div>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
$(function(){
	backset('../../<?php echo $groupMembers['GroupList']['backimg']; ?>');
	headimgset('../<?php echo $auth['userimg']; ?>');
});
<?php $this->Html->scriptEnd(); ?>
<?php echo $this->Html->script('chat', array('inline' => false)); ?>