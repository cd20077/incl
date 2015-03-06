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
			<h2 class="page_title"><?php echo __('パスワード再送');?></h2>
			<p class="txt_ex"><?php echo __('登録頂いたアドレスにパスワード再発行メールを送信しました。');?><br />
							  <?php echo __('メールを受信し確認を行ってください。');?><br /></p>
			<div class="btn"><?php echo $this->Html->link(__('ログイン画面へ'), array('controller'=>'auths','action'=>'login'), array('class' => 'btn_submit imgfade','escape'=>false)); ?></div>
		<!-- / #mainarea --></div>
        
	<!-- / #main --></div>
	<!-- ▲メインここまで -->
	
	<!-- ▼フッターここから -->
	<?php echo $this->element('footer')?>
	<!-- ▲フッターここまで -->
<!-- / #Contents --></div>