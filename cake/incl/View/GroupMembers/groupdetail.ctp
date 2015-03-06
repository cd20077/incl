
<?php echo $this->Html->css( 'form', array('inline' => false)); ?> 
<?php echo $this->Html->css( 'chat', array('inline' => false)); ?>
<div id="container">
    
    <!-- ▼メインここから -->
    <main>
    
        <div id="userart">
            <div id="gmenu">
            	<ul id="nav4"> 
                    <li class="b1"><?php echo $this->Html->link(__('ディスプレイ'), array('controller' => 'Filetops', 'action' => 'group',$groupMembers['GroupList']['ranid']), array( 'escape'=>false)); ?></li> 
                    <li class="b2"><a href="#inline_chat" class="chatopen"><?php echo __('チャット');?></a></li> 
                </ul> 
            </div>
        	<input type="hidden" id="proid" value="<?php echo $groupMembers['GroupList']['ranid']; ?>">
            <div id="userdiv" class = "cf">
                <div class="h2box2">
                	<h2 class="h2inbox2">
                    	<span id="nameid2"><?php echo $groupMembers['GroupList']['name']; ?></span>
						<input id="nameid2-edit" class="fm" name="nameid2" style="display:none;" required maxlength="16" />
                        
						<?php if($groupMembers['GroupMember']['auth_level_id']==1){ ?>
                            <span class="h2under2" id="channame2"><?php echo __('プロジェクト名変更');?></span>
                        <?php }else{ ?>
                            
                        <?php } ?>
                        
                    </h2>
                </div>
                <div id="topdiv">
                	<div id="" class="prodmenubox1">
                        <div class="inner">
                        	<div class="intitle">
                            	<?php echo __('リーダー');?>
                            </div>
                        	<div class="indimg" style="background-image: url('../../<?php echo $groupMembers['GroupList']['User']['userimg']; ?>');">
                            </div>
                        	<div class="produnder">
                            	<?php echo $groupMembers['GroupList']['User']['name']; ?>
                            </div>
                        </div>
                    </div>
                    <?php if($groupMembers['GroupMember']['auth_level_id']==1){ ?>
                		<div id="newmember" class="prodmenubox2">
                    <?php }else{ ?>
                		<div class="prodmenubox2">
                    <?php } ?>
                        <div class="inner">
                        	<div class="intitle">
                            	<?php echo __('メンバー追加');?>
                            </div>
                        	<div class="newmem"></div>
                        	<div class="newprounder">
                            	<?php echo __('残り');?>
                                <span id="atikt2">
                            	<?php
								if(isset($groupMembers['GroupList']['GroupMember'])){
									echo MAX_PROJECT_MEMBER - count($groupMembers['GroupList']['GroupMember']);
								}else{
									echo MAX_PROJECT_MEMBER;
								}
								?>
                                </span>
								<?php echo __('人追加可能');?>
                            </div>
                        </div>
                    </div>
                	<div id="" class="prodmenubox3">
                        <div class="inner">
                        	<div class="intitle">
                            	<?php echo __('使用領域');?>
                            </div>
                        	<div class="indata">
                                <div id = "dataleft">
                                    <span class="chart2" data-percent="<?php echo ceil($groupMembers['GroupList']['precapa'] / $groupMembers['GroupList']['maxcapa'] * 100);?>">
                                        <span class="percent2"></span>
                                    </span>
                                </div>
                                <div id = "dataright">
                                    <span class="sizetitle"><?php echo __('現在サイズ');?> / <?php echo __('最大サイズ');?></span>
                                    <span class="nowbytespan"><?php echo $groupMembers['GroupList']['precapa'];?></span>
                                    <span class="sumbytespan"> / <?php echo $groupMembers['GroupList']['maxcapa'];?> <?php echo __('バイト');?></span>
                                    <span class="chanposi"><?php echo __('サイズ拡張');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div id="seconddiv" class = "cf">
                
                    <div id="tabledivbox2" class = "prodebox2">
                        <table id="hor-minimalist3" summary="">
                            <thead>
                                <tr>
                                    <th scope="col"><?php echo __('プロジェクトメンバー名');?></th>
                                    <th scope="col"><?php echo __('権限');?></th>
                                    <th scope="col"><?php echo __('加入日時');?></th>
									<?php if($groupMembers['GroupMember']['auth_level_id']==1){ ?>
                                    <th scope="col"><?php echo __('編集');?></th>
                                    <?php }else{ ?>
                                        
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                        		<?php
								if(count($groupMembers)==0){
									print '<tr><td colspan="3">'.__('現在参加しているプロジェクトメンバーはいません').'</td></tr>';
								}else{
								?>
								<?php foreach ($groupMembers['GroupList']['GroupMember'] as $groupMember): ?>
                                <tr>
                                    <td>
										<?php echo '<div class="adminimg3" style="background-image: url(\'../../'.$groupMember['User']['userimg'].'\');"></div>'.$groupMember['User']['name']; ?>
                                    </td>
                                    <td>
                                    	<?php echo $groupMember['AuthLevel']['name']; ?>
                                    </td>
                                    <td>
                                    	<?php echo $groupMember['created']; ?>
                                    </td>
									<?php if($groupMembers['GroupMember']['auth_level_id']==1){ ?>
                                    <td>
										<?php if($groupMembers['GroupList']['user_id']==$groupMember['user_id']){
											echo __('リーダーを編集することはできません');
										}else{ ?>
                                            <input type="button" class="btn2" value="<?php echo __('権限変更');?>" onClick="chanauth('<?php echo $groupMember['id']; ?>','<?php echo $groupMember['User']['name']; ?>','<?php echo $groupMember['auth_level_id']; ?>')">
                                            <input type="button" class="btn2" value="<?php echo __('脱退');?>" onClick="memdel('<?php echo $groupMember['id']; ?>','<?php echo $groupMember['User']['name']; ?>')">
                                        <?php } ?>
                                    </td>
                                    <?php }else{ ?>
                                        
                                    <?php } ?>
                                </tr>
                                <?php endforeach;
								} ?>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                
            </div>
                
        </div>
    
    </main>
    <!-- ▲メインここまで -->
    
    
    <!-- ▼チャットここから -->
    <div style='display:none'>
        <div id='inline_chat'>
            <div id="containar2">
                <div id="Log">
                    <ul></ul>
                </div>
            </div>

            <div id="chat">
                <input type="text" name="str" id="str" class="inputtext">
                <input type="button" name="button1" id="button1" class="searchbtn" value="<?php echo __('送信');?>">
            </div>
        </div>
    </div>
    <!-- ▲チャットここまで -->
    
    <!-- ▼ヘッダーここから -->
    <?php echo $this->element('header')?>
    <!-- ▲ヘッダーここまで -->

<!-- / #wrap --></div>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
$(function(){
	headimgset('../<?php echo $auth['userimg']; ?>');
	topbackset('../<?php echo  $groupMembers['GroupList']['backimg']; ?>');
});
<?php $this->Html->scriptEnd(); ?>
<?php echo $this->Html->script('chat', array('inline' => false)); ?>