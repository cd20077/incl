<?php $this->layout = ""; ?>
<h2><?php echo $name; ?></h2>
<p class="error">
    <strong><?php echo __d('cake', 'エラー'); ?>: </strong>
    <?php printf(
        __d('cake', 'アクセスされた %s は見つかりません。'),
        "<strong>'{$url}'</strong>"
    ); ?>
</p>
<?php
if (Configure::read('debug') > 0 ):
    echo $this->element('exception_stack_trace');
endif;
?>