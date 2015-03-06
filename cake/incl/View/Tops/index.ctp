
<div id="container">
    
    <!-- ▼メインここから -->
    <main>
    
        <section class="cf">
            <div id="topleft">
            	<table>
                	<tr>
                        <td>
                            <?php echo $this->Html->link(__('<div id="logdiv">新規会員登録</div>'), array('controller'=>'users','action'=>'entry_input'), array('class' => 'loga', 'target' => '','escape'=>false)); ?>
                        </td>
                    </tr>
                	<tr>
                        <td>
                            <?php echo $this->Html->link(__('<div id="newdiv">ログイン</div>'), array('controller'=>'auths','action'=>'login'), array('id' => 'myElement', 'class' => 'loga','target' => '','escape'=>false)); ?>
                        </td>
                    </tr>
                </table>
            
            
            </div>
            <div id="topright">
            	<p>
                より直感的に、<br />
                使いやすいクラウドサービス
            	</p>
            </div>
    		
        </section>
        <!--
        <hr />
    
        <section>
        
    		
        </section>
        
        <hr />
    
        <section>
        
    		
        </section>
        -->
    
    </main>
    <!-- ▲メインここまで -->

    <hr />

    <footer role="contentinfo" id="footdiv">
        <address><?php echo __('&copy;incl All Rights Reserved.');?></address>
    </footer>
	
    <header>
        <div id="headmain">
            <h1 id="top">
            	<span class="maintitle"><?php //echo $this->Html->link(__('incl'), array('controller'=>'tops','action'=>'index'), array('class' => '', 'target' => '', 'escape'=>false)); ?><?php echo __('incl');?></span>
            </h1>
                
        </div>
    </header>
<!-- / #wrap --></div>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
<?php
if( !empty($getid)){
?>

$(function(){
	entrylink('<?php echo $getid; ?>');
});

<?php
}
?>
<?php $this->Html->scriptEnd(); ?>