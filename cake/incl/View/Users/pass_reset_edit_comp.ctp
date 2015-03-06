<div id="Contents">
	<!-- ▼ヘッダーここから -->
	<?php echo $this->element('header')?>
	<!-- ▲ヘッダーここまで -->

	<!-- ▼メインここから -->
	<div id="main" class="fbox">
	
        <!-- ▼ヘッダーここから -->
		<?php echo $this->element('side')?>
        <!-- ▲ヘッダーここまで -->
        
		<div id="mainarea">
			<h2 class="page_title"><?php echo __('パスワード再設定');?></h2>
			<p class="page_txt"><?php echo __('パスワードが再設定されました。');?></p>
			<p class="resetpass">
				<?php echo $this->Html->link(__('ツーリストログを利用する'), array('controller'=>'TouristLogs','action'=>'contribution_list'), array('class' => '','escape'=>false)); ?></div>
			</p>
		<!-- / #mainarea --></div>
        
	<!-- / #main --></div>
	<!-- ▲メインここまで -->
	
	<!-- ▼フッターここから -->
	<?php echo $this->element('footer')?>
	<!-- ▲フッターここまで -->
<!-- / #Contents --></div>