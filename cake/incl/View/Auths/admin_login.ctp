

	<div id="main">
	<div class="contents">
	
		<?php echo $this->Session->flash(); ?>
		
		<div id="login">
			<ul class="SUBsummary">
				<li>管理画面ログイン</li>
			</ul>

			<? echo $this->Form->create('Auths', array('inputDefaults'=>array('label'=>false))); ?>
			<dl>
			<dt>ログインID</dt>
			<dd><? echo $this->Form->input('login_cd', array('type'=>'text')) ?></dd>
			<dt>パスワード</dt>
			<dd><? echo $this->Form->input('login_pw', array('type'=>'password')) ?></dd>
			</dl>
			<? echo $this->Form->submit('ログイン'); ?>
			<? echo $this->Form->end(); ?>
		</div>

		
	</div>
	</div>


