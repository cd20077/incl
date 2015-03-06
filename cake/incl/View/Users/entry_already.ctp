
<!-- ▼メインここから -->
<div id="css3form">
    <h2 class="page_title"><?php echo __('新規会員登録');?></h2>
    <p class="page_txt"><?php echo __('このメールアドレスは会員登録済みです');?></p>
    <div class="ent_btn">
        <?php echo $this->Html->link(__('ログイン画面へ'), array('controller'=>'auths','action'=>'login'), array('class' => 'btn','escape'=>false)); ?>
    </div>
<!-- / #main --></div>
<!-- ▲メインここまで -->