
<table id="hor-minimalist2" summary="">
    <thead>
        <tr>
            <th scope="col"><?php echo __('プロジェクト名');?></th>
            <th scope="col"><?php echo __('リーダー名');?></th>
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
            <div id="adminimg" style="background-image: url(\'../'.$groupMember['GroupList']['User']['userimg'].'\');"></div>'
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