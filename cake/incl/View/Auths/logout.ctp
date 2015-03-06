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
            <h2 class="page_title"><?php echo __('ログアウト');?></h2>
            <p class="page_txt"><?php echo __('ログアウトしました。');?></p>
		</div>
        
	<!-- / #main --></div>
	<!-- ▲メインここまで -->
	
	<!-- ▼フッターここから -->
	<?php echo $this->element('footer')?>
	<!-- ▲フッターここまで -->
<!-- / #Contents --></div>