<footer role="contentinfo">
    <nav id="footAssist" role="navigation">
        <ul>
			<li id="folderzone">
				<span class="folder"><p><?php echo __('新規フォルダ');?></p></span>
			</li>
			<li id="uploadzone" dropzone>
				<span class="upload" onClick="$('#fileup').click();"><p><?php echo __('アップロード');?></p></span>
                <input type="file" id="fileup" style="display:none;" multiple />
			</li>
			<li id="downloadzone" dropzone>
				<span class="download"><p><?php echo __('ダウンロード');?></p></span>
			</li>
			<li id="trashzone" dropzone>
				<span class="trash"><p><?php echo __('ファイル削除');?></p></span>
			</li>
			<li id="chenzone" dropzone>
				<span class="backchenge" onClick="$('#backimgup').click();"><p><?php echo __('壁紙変更');?></p></span>
                <input type="file" id="backimgup" accept="image/*" style="display:none;" />
			</li>
        </ul>
    </nav>
</footer>