<div id="container">
    
    <!-- ▼メインここから -->
    <main>
    
        <div id="userart">
        
            <div id="userdiv" class = "cf">
                <div class="h2box">
                	<h2 class="h2inbox"><?php echo __('個人設定');?></h2>
                </div>
                <div id="firstdiv" class = "cf">
                    <div class = "topuserbox1">
                    	<div id="topimgbox">
                        	
                            <div id="userimgid"></div>
                        	<?php //echo $this->Html->image('../'.$auth['userimg'], array('class' => 'headimg','id'=>'userimgid','escape'=>false)); ?>
                        </div>
                    	<div id="topimgund">
                            <span id="chanimg" class="chanposi" onClick="$('#inputfile1').click();"><?php echo __('ユーザーイメージ変更');?></span>
                            <input type="file" id="inputfile1" accept="image/*" style="display:none;" />
                            
                        </div>
                    </div>
                    <div class = "topuserbox2">
                    	<div id="topname">
                            <div class="a"><h3 class="h3cl"><?php echo __('ユーザー名');?></h3></div>
                            
                            <div class="topptag">
                            	<span id="nameid"><?php echo $auth['name']; ?></span>
								<input id="nameid-edit" class="fm" name="nameid" style="display:none;" required maxlength="16" />
                            </div>
                            
                            <span id="channame" class="chanposi"><?php echo __('ユーザー名変更');?></span>
                            
                        </div>
                    	<div id="topmail">
                            <div class="a"><h3 class="h3cl"><?php echo __('登録メールアドレス');?></h3></div>
                            
                            <div class="topptag">
                            	<?php echo $auth['mail']; ?>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                    <div class = "topuserbox2">
                        <div class="a"><h3 class="h3cl"><?php echo __('使用領域');?></h3></div>
                    	<div id = "dataleft">
                            <span class="chart" data-percent="<?php echo ceil($auth['precapa'] / $auth['maxcapa'] * 100);?>">
                                <span class="percent"></span>
                            </span>
                        </div>
                    	<div id = "dataright">
                        	<table>
                                <tr>
                                    <th class="">
                                    	<span><?php echo __('現在サイズ');?> / <?php echo __('最大サイズ');?></span>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="nowbytespan"><?php echo $auth['precapa'];?></span>
                                    	<span class="sumbytespan"> / <?php echo $auth['maxcapa'];?> <?php echo __('バイト');?></span>
                                    </td>
                                </tr>
                            </table>
                            <span class="chanposi"><?php echo __('サイズ拡張');?></span>
                        </div>
                    </div>
                </div>
                
                <hr />
                
                <div id="seconddiv" class = "cf">
                
                    <div class = "topuserbox3">
                        <div class="a"><h3 class="h3cl"><?php echo $this->Html->link(__('プロジェクト'), array('controller' => 'Users', 'action' => 'grouptop'), array( 'escape'=>false,'class'=>'h3aclass')); ?></h3></div>
                        <table id="hor-minimalist" summary="">
                            <thead>
                                <tr>
                                    <th scope="col"><?php echo __('プロジェクト名');?></th>
                                    <th scope="col"><?php echo __('リーダー');?></th>
                                    <th scope="col"><?php echo __('最終更新日時');?></th>
                                </tr>
                            </thead>
                            <tbody>
                            	
                        		<?php
								if(count($groupMembers)==0){
									print '<tr><td colspan="3">'.__('現在参加しているプロジェクトはありません').'</td></tr>';
								}else{
								?>
								<?php foreach ($groupMembers as $groupMember): ?>
                                <tr>
                                	<td>
									<?php echo $this->Html->link(h($groupMember['GroupList']['name']), array('controller' => 'GroupMembers', 'action' => 'groupdetail', $groupMember['GroupList']['ranid']), array( 'escape'=>false,'class'=>'tableatag')); ?>
                                    </td>
                                    <td>
                                    <?php echo $this->Html->link(h($groupMember['GroupList']['User']['name']), array('controller' => 'GroupMembers', 'action' => 'groupdetail', $groupMember['GroupList']['ranid']), array( 'escape'=>false,'class'=>'tableatag')); ?>
                                    </td>
                                    <td>
                                    <?php echo $this->Html->link(h($groupMember['GroupList']['modified']), array('controller' => 'GroupMembers', 'action' => 'groupdetail', $groupMember['GroupList']['ranid']), array( 'escape'=>false,'class'=>'tableatag')); ?>
                                    </td>
                                </tr>
                                <?php endforeach;
								} ?>
                            </tbody>
                        </table>
                    </div>
                    <div class = "topuserbox3">
                        <div class="a"><h3 class="h3cl"><?php echo __('更新履歴');?></h3></div>
                    	<div id="information">
                            <ul>
                                <?php foreach ($news as $news): ?>
                                <li>
                                    <?php echo $this->Html->link(
									'<span>'.h($news['News']['modified']).'</span>'.h($news['News']['title'])
									, array('controller' => 'Filetops', 'action' => 'index')
									, array( 'escape'=>false,'class'=>'tableatag')); ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <span class="chanposi"><?php echo __('以前の更新情報へ');?></span>
                        </div>
                    </div>
                    
                </div>
                
            </div>
                
        </div>
    
    </main>
    <!-- ▲メインここまで -->
	
    <!-- ▼ヘッダーここから -->
    <?php echo $this->element('header')?>
    <!-- ▲ヘッダーここまで -->

<!-- / #wrap --></div>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
$(function(){
	headimgset('<?php echo $auth['userimg']; ?>');
	topbackset('<?php echo $auth['backimg']; ?>');
	topuserimgset('<?php echo $auth['userimg']; ?>');
});
<?php $this->Html->scriptEnd(); ?>