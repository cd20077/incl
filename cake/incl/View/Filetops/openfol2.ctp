<?php $i=0; ?>
<?php foreach ($dir as $dir1): ?>
    <?php if($i == 0){ ?>
            <?php foreach ($dir1 as $dir2): ?>
            <?php //if($i != 0){ ?>
            <div id="<?php echo $folname.h($dir2); ?>" data-dirname="<?php echo $folname; ?>" data-fname="<?php echo h($dir2); ?>" class="folderdiv inofdiv">
                <span class="folderd"><p><?php echo h($dir2); ?></p></span>
            </div>
        <?php endforeach; ?>
    <?php }else{ ?>
                            <?php foreach ($dir1 as $dir2): ?>
            <?php //if($i != 0){ ?>
            <div id="<?php echo $folname.h($dir2); ?>" data-dirname="<?php echo $folname; ?>" data-fname="<?php echo h($dir2); ?>" class="filediv inofdiv">
                <span class="file"><p><?php echo h($dir2); ?></p></span>
            </div>
        <?php endforeach; ?>
    <?php } ?>
    <?php $i++; ?>
<?php endforeach; ?>