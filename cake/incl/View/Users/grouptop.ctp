
<?php echo $this->Html->css( 'form', array('inline' => false)); ?> 
<div id="container">
    
    <!-- ▼メインここから -->
    <main>
    
        <div id="userart">
        
            <div id="userdiv" class = "cf">
            	
                <div class="h2box">
                    <h2 class="h2inbox"><?php echo __('プロジェクト');?></h2>
                </div>
                <div id="topdiv">
                	<div id="" class="promenubox">
                        <div class="inner">
                        	<div class="intitle">
                            	<?php echo __('参加プロジェクト');?>
                            </div>
                        	<div id="inpronum" class="innum">
                            	<?php echo count($groupMembers); ?>
                            </div>
                        </div>
                    </div>
                	<div id="" class="promenubox">
                        <div class="inner">
                        	<div class="intitle">
                            	<?php echo __('リーダープロジェクト');?>
                            </div>
                        	<div id="adpronum" class="innum">
                            	<?php
								if(isset($admincnt[0]['admincnt'])){
									echo $admincnt[0]['admincnt'];
								}else{
									echo 0;
								}
								?>
                            </div>
                        </div>
                    </div>
                	<div id="newproject" class="promenubox2">
                        <div class="inner">
                        	<div class="intitle">
                            	<?php echo __('新規プロジェクト');?>
                            </div>
                        	<div class="newpro"></div>
                        	<div class="newprounder">
                            	<?php echo __('残り');?>
                                <span id="atikt">
                            	<?php
								if(isset($admincnt[0]['admincnt'])){
									echo MAX_PROJECT_NUM - $admincnt[0]['admincnt'];
								}else{
									echo MAX_PROJECT_NUM;
								}
								?>
                                </span>
								<?php echo __('つまで作成可能');?>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div id="underdiv">
    
    				<?php echo $this->Session->flash(); ?>
                    
					<?php //echo $this->Html->link('詳細へ', array('controller' => 'GroupMembers', 'action' => 'groupdetail'), array( 'escape'=>false,'class'=>'tableatag')); ?>
                	<div id="tabledivbox" class="probox">
                        <table id="hor-minimalist2" summary="">
                            <thead>
                                <tr>
                                    <th scope="col"><?php echo __('プロジェクト名');?></th>
                                    <th scope="col"><?php echo __('リーダー');?></th>
                                    <th scope="col"><?php echo __('最終更新日時');?></th>
                                    <th scope="col"><?php echo __('加入日時');?></th>
                                </tr>
                            </thead>
                            <tbody id="protbody">
                            	
                        		<?php
								if(count($groupMembers)==0){
									print '<tr><td colspan="4">'.__('現在参加しているプロジェクトはありません').'</td></tr>';
								}else{
								?>
								<?php foreach ($groupMembers as $groupMember): ?>
                                <tr>
                                	<td>
									<?php echo $this->Html->link(h($groupMember['GroupList']['name']), array('controller' => 'GroupMembers', 'action' => 'groupdetail', $groupMember['GroupList']['ranid']), array( 'escape'=>false,'class'=>'tableatag')); ?>
                                    </td>
                                    <td><?php echo $this->Html->link('
                                    <div class="adminimg" style="background-image: url(\'../'.$groupMember['GroupList']['User']['userimg'].'\');"></div>'
                                    .h($groupMember['GroupList']['User']['name']), array('controller' => 'GroupMembers', 'action' => 'groupdetail', $groupMember['GroupList']['ranid']), array( 'escape'=>false,'class'=>'tableatag')); ?>
                                    </td>
                                    <td>
                                    <?php echo $this->Html->link(h($groupMember['GroupList']['modified']), array('controller' => 'GroupMembers', 'action' => 'groupdetail', $groupMember['GroupList']['ranid']), array( 'escape'=>false,'class'=>'tableatag')); ?>
                                    </td>
                                    <td>
                                    <?php echo $this->Html->link(h($groupMember['GroupMember']['created']), array('controller' => 'GroupMembers', 'action' => 'groupdetail', $groupMember['GroupList']['ranid']), array( 'escape'=>false,'class'=>'tableatag')); ?>
                                    </td>
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
	
    <!-- ▼ヘッダーここから -->
    <?php echo $this->element('header')?>
    <!-- ▲ヘッダーここまで -->

<!-- / #wrap --></div>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
$(function(){
	headimgset('<?php echo $auth['userimg']; ?>');
	topbackset('<?php echo $auth['backimg']; ?>');
});
<?php $this->Html->scriptEnd(); ?>