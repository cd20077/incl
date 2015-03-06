
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
                    echo '作成者を編集することはできません';
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