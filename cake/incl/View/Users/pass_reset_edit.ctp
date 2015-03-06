<div id="Contents">
	<!-- ▼ヘッダーここから -->
	<?php echo $this->element('header')?>
	<!-- ▲ヘッダーここまで -->

	<!-- ▼メインここから -->
	<div id="main" class="fbox">
	
        <!-- ▼メニューここから -->
		<?php echo $this->element('side')?>
        <!-- ▲メニューここまで -->
		
		<div id="mainarea">
			<h2 class="page_title"><?php echo __('パスワード再設定');?></h2>
			
            <?php echo $this->Form->create('User',array('novalidate' => true)); ?>
	            
			<dl class="form_dl">
	            
				<dt>メールアドレス</dt>
				<dd><?php echo h($address['User']['mail']); ?></dd>
			</dl>		
			<dl class="form_dl">
				<dt><?php echo __('パスワード');?></dt>
				
                <dd>
					<?php echo $this->Form->error('User.password', null, array('escape' => false)); ?>
					<?php echo $this->Form->input('User.password', array('value' => '','label' => false,'div' => false, 'error' => false)); ?>
                </dd>
			</dl>
			<div class="btn">
				<?php echo $this->Form->submit(__('パスワードを再設定する'), array('div' => false,'class'=>'btn_submit imgfade')); ?>
			</div>
			
				<?php echo $this->Form->end(); ?>
			
		</div>
					
	<!-- / #main --></div>
	<!-- ▲メインここまで -->
	
	<!-- ▼フッターここから -->
	<?php echo $this->element('footer')?>
	<!-- ▲フッターここまで -->
<!-- / #Contents --></div>