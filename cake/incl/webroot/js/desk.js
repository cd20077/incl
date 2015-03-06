
$(function(){
	$('#dropzone').haloContext({
            bindings:{
                '個人設定':function(){
                    location.href = './users/usertop';
                },
                'プロジェクト':function(){
                    location.href = './users/grouptop';
                },
                'アップロード':function(){
                    upfunc();
                },
                '新規フォルダ':function(){
                    folapp();
                },
            }
	});
	
	rcfunc();
        
	$( "#trashzone" ).droppable({
		accept : ".filediv,.folderdiv" , // 受け入れる要素を指定
                hoverClass: 'foothover',
                tolerance: 'pointer',
                greedy: true,
		drop : function(event , ui){
			if($(ui.draggable).hasClass('folderdiv')){
                            //  dragされてきたオブジェクトを取得してクローン作製
                            var dragId = ui.draggable.attr("id");
                            //alert(dragId);
                            if (confirm(dragId+'を削除しますか？')) {
                                $.ajax({
                                    url: "./Ajaxfuncs/ajaxfoltrash",
                                    type: "POST",
                                    data: {name : dragId},
                                    dataType: "text",
                                    success : function(response){
                                        //通信成功時の処理
                                        if(response=="not"){
                                            alert('該当のフォルダが存在しません。');
                                        }else if(response=="direrr"){
                                            alert('削除に失敗しました。');
                                        }else if(response=="ok"){
                                            ui.draggable.remove();
                                        }else{
                                            alert('削除に失敗しました。');
                                        }
                                    },
                                    error: function(){
                                        //通信失敗時の処理
                                        alert('通信失敗');
                                    }
                                });
                            } else {
                                //alert("キャンセルボタンがクリックされました");
                            }
			}else if($(ui.draggable).hasClass('filediv')){
                            //  dragされてきたオブジェクトを取得してクローン作製
                            var dragId = ui.draggable.attr("id");
                            var fname = ui.draggable.attr("data-fname");
                            var dname = ui.draggable.attr("data-dirname");
                            //alert(dragId);
                            if (confirm(dragId+'を削除しますか？')) {
                                //alert("OKボタンがクリックされました");
                                $.ajax({
                                    url: "./Ajaxfuncs/ajaxtrash",
                                    type: "POST",
                                    data: {
                                        name : dragId,
                                        fname : fname,
                                        dname : dname
                                    },
                                    dataType: "text",
                                    success : function(response){
                                        //通信成功時の処理
                                        if(response=="not"){
                                            alert('該当のファイルが存在しません。');
                                        }else if(response=="direrr"){
                                            alert('削除に失敗しました。');
                                        }else if(response=="ok"){
                                            //alert(dragId);
                                            ui.draggable.remove();
                                        }else{
                                            alert('削除に失敗しました。');
                                        }
                                    },
                                    error: function(){
                                        //通信失敗時の処理
                                        alert('通信失敗');
                                    }
                                });
                            } else {
                                //alert("キャンセルボタンがクリックされました");
                            }
			}else{
				
			}
		},
		out : function (event , ui){
			
		}

	});
	
	$( "#chenzone" ).droppable({
		accept : ".filediv" , // 受け入れる要素を指定
                hoverClass: 'foothover',
                tolerance: 'pointer',
                greedy: true,
		drop : function(event , ui){
                    var dragId = ui.draggable.attr("id");
                    var fname = ui.draggable.attr("data-fname");
                    var dname = ui.draggable.attr("data-dirname");

                    var ext = dragId.split(".").reverse();
                    ext_nm = ext[0];
                    if(!ext_nm.match(/(jpg|gif|png)/i)){
                        alert("jpg,gif,pngファイルのみ背景に設定できます。");
                    }else{
                        if (confirm('壁紙を変更しますか？')) {
                            $.ajax({
                                url: "./Ajaxfuncs/ajaxchange",
                                type: "POST",
                                data: {
                                    name : dragId,
                                    fname : fname,
                                    dname : dname
                                },
                                dataType: "text",
                                success : function(response){
                                    if(response=="not"){
                                        alert('該当のファイルが存在しません。');
                                    }else if(response=="direrr"){
                                        alert('背景の変更に失敗しました。');
                                    }else if(response=='err'){
                                        alert('背景の変更に失敗しました。');
                                    }else{
                                        backset(response);
                                    }
                                },
                                error: function(){
                                    //通信失敗時の処理
                                    alert('通信失敗');
                                }
                            });
                        } else {
                            //alert("キャンセルボタンがクリックされました");
                        }

                    }
		},
		out : function (event , ui){
			
		}

	});
	
	$( "#downloadzone" ).droppable({
            accept : ".filediv,.folderdiv" , // 受け入れる要素を指定
            hoverClass: 'foothover',
            tolerance: 'pointer',
            greedy: true,
            drop : function(event , ui){
                if($(ui.draggable).hasClass('folderdiv')){
                    var dragId = ui.draggable.attr("id");
                    $.colorbox({
                        href:"./Ajaxfuncs/ajaxdownfol/"+dragId.replace(/\u002f/g,'*'),
                        width:"0px",
                        height:"0px",
                        iframe:true
                    });
                }else if($(ui.draggable).hasClass('filediv')){
                    var dragId = ui.draggable.attr("id");
                    $.colorbox({
                        href:"./Ajaxfuncs/ajaxdownfile/"+dragId.replace('/','*'),
                        width:"0px",
                        height:"0px",
                        iframe:true
                    });
                }else{

                }
            },
            out : function (event , ui){

            }

	});
	
	$('#dropzone').on('drop', function(event) {
            event.preventDefault();
            
            var dataTransfer = event.originalEvent.dataTransfer;
            if (dataTransfer && dataTransfer.items){
                var items = dataTransfer.items, 
                len   = items.length,
                i, entry;
                for(i=0; i<len; i++){
                       entry = items[i]
                       if(entry.getAsEntry){  //Standard HTML5 API
                          entry = entry.getAsEntry();
                       }else if(entry.webkitGetAsEntry){  //WebKit implementation of HTML5 API.
                          entry = entry.webkitGetAsEntry();
                       }
                       if(entry.isFile){
                            readFile(entry, uploadFile);
                       }else if(entry.isDirectory){
                            readFileTree(entry, uploadFile);
                              
                       }
                }
            }
            return false;
				
	}).on('dragover', function(event) {
		return false;
	});
	$("#fileup").change( function() {
            var file = $('#fileup');
            var formData = new FormData();
            for (var i = 0; i < file.prop("files").length; i++) {
                formData.append('file', file.prop("files")[i]);

                $.ajax('./Ajaxfuncs/ajaxupfile', {
                    method: 'POST',
                    contentType: false,
                    processData: false,
                    data:formData,
                    dataType: "text",
                    success: function(response) {
                        console.log('アップロードに成功しました');
                        console.log(response);
                        dataup(response);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log( textStatus );
                        console.log( errorThrown );
                    }
                });
            }
	});
	$('#uploadzone').on('drop', function(event) {
            event.preventDefault();
            
            var dataTransfer = event.originalEvent.dataTransfer;
            if (dataTransfer && dataTransfer.items){
                var items = dataTransfer.items, 
                    len   = items.length,
                    i, entry;
                for(i=0; i<len; i++){
                    entry = items[i]
                    if(entry.getAsEntry){  //Standard HTML5 API
                       entry = entry.getAsEntry();
                    }else if(entry.webkitGetAsEntry){  //WebKit implementation of HTML5 API.
                       entry = entry.webkitGetAsEntry();
                    }
                    if(entry.isFile){
                         //Handle FileEntry
                         readFile(entry, uploadFile);
                    }else if(entry.isDirectory){
                         //Handle DirectoryEntry
                         readFileTree(entry, uploadFile);

                    }
                }
            }
            return false;
				
	}).on('dragover', function(event) {
		return false;
	});
	$("#backimgup").change( function() {
            var file = $('#backimgup');
            var formData = new FormData();
            formData.append('file', file.prop("files")[0]);

            $.ajax('./Ajaxfuncs/ajaxupbackimg', {
                method: 'POST',
                contentType: false,
                processData: false,
                data:formData,
                dataType: "text",
                success: function(data) {
                    backset(data);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert('背景の変更に失敗しました。');
                    console.log( textStatus );
                    console.log( errorThrown );
                }
            });
	});
	$('#folderzone').click(function() {
            folapp();
	});
});

function dataup(result){
	$("#dropzone").append('<div id="'+result+'" data-dirname="" data-fname="'+result+'" class="filediv outfdiv"><span class="file"><p>'+result+'</p></span></div>');
	rcfunc();
	
}

function folup(result){
	$("#dropzone").append('<div id="'+result+'" data-dirname="" data-fname="'+result+'" class="folderdiv outfdiv"><span class="folderd"><p>'+result+'</p></span></div>');
	rcfunc();
	
}

function dataup2(dir,result){
        
        htmldata='<div id="'+dir+result+'" data-dirname="'+dir+'" data-fname="'+result+'" class="filediv inofdiv"><span class="file"><p>'+result+'</p></span></div>';
        $('div[id="in'+dir+'"]').append(htmldata);
	rcfunc();
	
}

function folup2(dir,result){
	htmldata='<div id="'+dir+result+'" data-dirname="'+dir+'" data-fname="'+result+'" class="folderdiv inofdiv"><span class="folderd"><p>'+result+'</p></span></div>';
	$('div[id="in'+dir+'"]').append(htmldata);
        rcfunc();
	
}

function backset(backimg){
	$('article').css('background-image','url('+backimg.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&')+')');	
}

function folapp(){
    $.colorbox({
        html:'<ul id="css3form"><h2>新規フォルダ</h2><span id="addcont"><li>フォルダ名を入力してください。</li><li><input type="text" class="fm" id="newfolname" value="" required /></li><p id="promem_err_mes" class="err_mes"></p><input type="button" id="newfolbtn" class="btn" value="フォルダを作成する" /></span></ul>',
        width:"500px",
        height:"350px",
        fixed: true
    });
    $('#newfolbtn').click(function() {
        $('#promem_err_mes').html('');
        var str = $('#newfolname').val();
        if(str==''){
            $('#promem_err_mes').html('フォルダ名が未入力です。');
        }else if( str.match( /^.*[(\\|\"|/|:|\*|?|<|>|\|)].*$/)){
            $('#promem_err_mes').html('フォルダ名に / : * ? &quot; &lt; &gt; |は使えません。');
        }else{
            var folname = $('#newfolname').val();
            $.ajax({
                url: "./Ajaxfuncs/ajaxappfol",
                type: "POST",
                data: {
                    name : folname
                },
                dataType: "text",
                success : function(response){
                    //通信成功時の処理
                    if(response=="direrr"){
                        $.colorbox.close();
                        alert('フォルダの作成に失敗しました。');
                    }else if(response=="err"){
                        $.colorbox.close();
                        alert('フォルダの作成に失敗しました。');
                    }else{
                        $.colorbox.close();
                        folup(response);
                    }
                },
                error: function(){
                    //通信失敗時の処理
                    alert('通信失敗');
                }
            });
        }

    });
}

function upfunc(){
    $.colorbox({
        html:'<ul id="css3form"><h2>アップロード</h2><span id="addcont"><li>ドラッグ&ドロップでもアップロード可能です。</li><input type="button" id="newfolbtn" class="btn" value="ファイルを選択する。" onClick="$(\'#fileup2\').click();" /></span><input type="file" id="fileup2" style="display:none;" multiple /></ul>',
        width:"500px",
        height:"350px",
        fixed: true
    });
    $("#fileup2").change( function() {
        var file = $('#fileup2');
        var formData = new FormData();
        for (var i = 0; i < file.prop("files").length; i++) {
            formData.append('file', file.prop("files")[i]);

            $.ajax('./Ajaxfuncs/ajaxupfile', {
                method: 'POST',
                contentType: false,
                processData: false,
                data:formData,
                dataType: "text",
                success: function(response) {
                    console.log('アップロードに成功しました');
                    console.log(response);
                    $.colorbox.close();
                    dataup(response);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log( textStatus );
                    console.log( errorThrown );
                    $.colorbox.close();
                }
            });
        }
    });
}

function rcfunc(){
	$('.folderdiv').haloContext({
		bindings:{
			'開く':function(){
                            var dragId = $(this).attr('title');
                            $.ajax({
                                url: "./Filetops/openfol",
                                type: "POST",
                                data: {
                                    id : dragId
                                },
                                dataType: "text",
                                success : function(response){
                                    if(response=="direrr"){
                                        alert('フォルダを開けませんでした。');
                                    }else if(response=="err"){
                                        alert('該当のフォルダが存在しません');
                                    }else{
                                        $('div[id="op'+dragId+'"]').remove();
                                        $("#dropzone").append('<div id="op'+dragId+'" data-fname="'+dragId+'" class="folopendiv ui-widget-content"><div class="fotitle">'+dragId+'<span id="close'+dragId+'" class="folclose"></span></div><div id="in'+dragId+'/" class="fobox">'+response+'</div></div>');
                                        $('span[id="close'+dragId+'"]').click(function() {
                                            $('div[id="op'+dragId+'"]').remove();
                                        });
                                        rcfunc();
                                    }
                                }
                            });
			},
			'フォルダ名変更':function(){
				var dragId = $(this).attr('title');
				var fname = $('div[id="'+dragId+'"]').attr("data-fname");
				var dname = $('div[id="'+dragId+'"]').attr("data-dirname");
				$.colorbox({
                                    html:'<ul id="css3form"><h2>フォルダ名変更</h2><span id="addcont"><li>新しいフォルダ名を入力してください。</li><li>現在：'+fname+'</li><li><input type="text" class="fm" id="refolname" maxlength="50" value="'+fname+'" required /></li><p id="promem_err_mes" class="err_mes"></p><input type="button" id="refolbtn" class="btn" value="フォルダ名を変更する" /></span></ul>',
                                    width:"500px",
                                    height:"400px",
                                    fixed: true
				});
				$('#refolbtn').click(function() {
                                    $('#promem_err_mes').html('');
                                    var str = $('#refolname').val();
                                    if(str==''){
                                        $('#promem_err_mes').html('フォルダ名が未入力です。');
                                    }else if( str.match( /^.*[(\\|\"|/|:|\*|?|<|>|\|)].*$/)){
                                        $('#promem_err_mes').html('フォルダ名に / : * ? &quot; &lt; &gt; |は使えません。');
                                    }else{
                                        var folname = $('#refolname').val();
                                        $.ajax({
                                            url: "./Ajaxfuncs/ajaxrename",
                                            type: "POST",
                                            data: {
                                                dtype : 'fol',
                                                bename :fname,
                                                afname : folname,
                                                dname : dname
                                            },
                                            dataType: "text",
                                            success : function(response){
                                                //通信成功時の処理
                                                if(response=="not"){
                                                    $.colorbox.close();
                                                    alert('該当のフォルダが存在しません。');
                                                }else if(response=="direrr"){
                                                    $.colorbox.close();
                                                    alert('フォルダ名の変更に失敗しました。');
                                                }else if(response=="err"){
                                                    $.colorbox.close();
                                                    alert('フォルダ名の変更に失敗しました。');
                                                }else{
                                                    $.colorbox.close();
                                                    $('div[id="'+dragId+'"]').remove();
                                                    if(dname==''){
                                                        folup(response);
                                                    }else{
                                                        folup2(dname,response);
                                                    }
                                                }
                                            },
                                            error: function(){
                                                //通信失敗時の処理
                                                alert('通信失敗');
                                            }
                                        });
                                    }
				});
			},
			'複製':function(){
                            var dragId = $(this).attr('title');
                            var fname = $('div[id="'+dragId+'"]').attr("data-fname");
                            var dname = $('div[id="'+dragId+'"]').attr("data-dirname");
                            $.ajax({
                                url: "./Ajaxfuncs/ajaxfcopy",
                                type: "POST",
                                data: {
                                    name : dragId,
                                    fname : fname,
                                    dname : dname
                                },
                                dataType: "text",
                                success : function(response){
                                    if(response=="not"){
                                        alert('該当のフォルダが存在しません。');
                                    }else if(response=="direrr"){
                                        alert('フォルダのコピーに失敗しました。');
                                    }else if(response=="err"){
                                        alert('フォルダのコピーに失敗しました。');
                                    }else{
                                        if(dname==''){
                                            folup(response);
                                        }else{
                                            folup2(dname,response);
                                        }
                                    }
                                },
                                error: function(){
                                    //通信失敗時の処理
                                    alert('通信失敗');
                                }
                            });
			},
			'削除':function(){
                            var dragId = $(this).attr('title');
                            if (confirm(dragId+'を削除しますか？')) {
                                $.ajax({
                                    url: "./Ajaxfuncs/ajaxfoltrash",
                                    type: "POST",
                                    data: { name : dragId},
                                    dataType: "text",
                                    success : function(response){
                                        //通信成功時の処理
                                        if(response=="not"){
                                            alert('該当のフォルダが存在しません。');
                                        }else if(response=="direrr"){
                                            alert('削除に失敗しました。');
                                        }else if(response=="ok"){
                                            $('div[id="'+dragId+'"]').remove();
                                        }else{
                                            alert('削除に失敗しました。');
                                        }
                                    },
                                    error: function(){
                                        //通信失敗時の処理
                                        alert('通信失敗');
                                    }
                                });
                            } else {
                                //alert("キャンセルボタンがクリックされました");
                            }
			},
			'ダウンロード':function(){
                            $.colorbox({
                                href:"./Ajaxfuncs/ajaxdownfol/"+$(this).attr('title').replace(/\u002f/g,'*'),
                                width:"0px",
                                height:"0px",
                                iframe:true
                            });
			},
		}
	});
	$('.filediv').haloContext({
		bindings:{
			'開く':function(){
				var dragId = $(this).attr('title');
				var ext = dragId.split(".").reverse();
				ext_nm = ext[0];
				if(ext_nm.match(/(jpg|gif|png)/i)){
                                    $.ajax({
                                        url: "./Ajaxfuncs/ajaxopen",
                                        type: "POST",
                                        data: {
                                            name : dragId
                                        },
                                        dataType: "text",
                                        success : function(response){
                                            if(response=="direrr"){
                                                alert('ファイルを開けませんでした。');
                                            }else if(response=='err1'){
                                                alert('ファイルが存在しません。');
                                            }else{
                                                $.colorbox({
                                                    href: response,
                                                    maxWidth:"100%",
                                                    maxHeight:"100%",
                                                });
                                                $(window).resize(function(){
                                                    $.colorbox.resize({width:'90%', height:'90%'});
                                                });

                                            }
                                        },
                                        error: function(){
                                            //通信失敗時の処理
                                            alert('通信失敗');
                                        }
                                    });
				}else if(ext_nm.match(/(txt|js|css)/i)){
                                    $.ajax({
                                        url: "./Ajaxfuncs/ajaxopen",
                                        type: "POST",
                                        data: {
                                            name : dragId
                                        },
                                        dataType: "text",
                                        success : function(response){
                                            if(response=="direrr"){
                                                alert('ファイルを開けませんでした。');
                                            }else if(response=='err1'){
                                                alert('ファイルが存在しません。');
                                            }else{
                                                $.colorbox({
                                                    href: response,
                                                    width:"90%",
                                                    height:"90%",
                                                    iframe:true
                                                });
                                                $(window).resize(function(){
                                                    $.colorbox.resize({width:'90%', height:'90%'});
                                                });
                                            }
                                        },
                                        error: function(){
                                            //通信失敗時の処理
                                            alert('通信失敗');
                                        }
                                    });
				}else{
					alert("ファイル形式が対応しておりません。");
					
				}
			},
			'ファイル名変更':function(){
                            var dragId = $(this).attr('title');
                            var fname = $('div[id="'+dragId+'"]').attr("data-fname");
                            var dname = $('div[id="'+dragId+'"]').attr("data-dirname");
                            var ext = fname.match(/(.+)(\.[^.]+$)/)[1];
                            $.colorbox({
                                html:'<ul id="css3form"><h2>ファイル名変更</h2><span id="addcont"><li>新しいファイル名を入力してください。</li><li>(拡張子は含みません。最大50文字まで。)</li><li>現在：'+fname+'</li><li><input type="text" class="fm" maxlength="50" id="refilename" value="'+ext+'" required /></li><p id="promem_err_mes" class="err_mes"></p><input type="button" id="refilebtn" class="btn" value="ファイル名を変更する" /></span></ul>',
                                width:"500px",
                                height:"400px",
                                fixed: true
                            });
                            $('#refilebtn').click(function() {
                                $('#promem_err_mes').html('');
                                var str = $('#refilename').val();
                                if(str==''){
                                    $('#promem_err_mes').html('ファイル名が未入力です。');
                                }else if( str.match( /^.*[(\\|\"|/|:|\*|?|<|>|\|)].*$/)){
                                    $('#promem_err_mes').html('ファイル名に / : * ? &quot; &lt; &gt; |は使えません。');
                                }else{
                                    var folname = $('#refilename').val();
                                    $.ajax({
                                        url: "./Ajaxfuncs/ajaxrename",
                                        type: "POST",
                                        data: {
                                            dtype : 'file',
                                            bename : fname,
                                            afname : folname,
                                            dname : dname
                                        },
                                        dataType: "text",
                                        success : function(response){
                                            //通信成功時の処理
                                            if(response=="not"){
                                                $.colorbox.close();
                                                alert('該当のファイルが存在しません。');
                                            }else if(response=="direrr"){
                                                $.colorbox.close();
                                                alert('ファイル名の変更に失敗しました。');
                                            }else if(response=="err"){
                                                $.colorbox.close();
                                                alert('ファイル名の変更に失敗しました。');
                                            }else{
                                                $.colorbox.close();
                                                $('div[id="'+dragId+'"]').remove();
                                                if(dname==''){
                                                    dataup(response);
                                                }else{
                                                    dataup2(dname,response);
                                                }
                                            }
                                        },
                                        error: function(){
                                            //通信失敗時の処理
                                            alert('通信失敗');
                                        }
                                    });
                                }

                            });
			},
			'複製':function(){
                            var dragId = $(this).attr('title');
                            var fname = $('div[id="'+dragId+'"]').attr("data-fname");
                            var dname = $('div[id="'+dragId+'"]').attr("data-dirname");
                            $.ajax({
                                url: "./Ajaxfuncs/ajaxcopy",
                                type: "POST",
                                data: {
                                    name : dragId,
                                    fname : fname,
                                    dname : dname
                                },
                                dataType: "text",
                                success : function(response){
                                    if(response=="not"){
                                        alert('該当のファイルが存在しません。');
                                    }else if(response=="direrr"){
                                        alert('ファイルのコピーに失敗しました。');
                                    }else if(response=="err"){
                                        alert('ファイルのコピーに失敗しました。');
                                    }else{
                                        if(dname==''){
                                            dataup(response);
                                        }else{
                                            dataup2(dname,response);
                                        }
                                    }
                                },
                                error: function(){
                                    //通信失敗時の処理
                                    alert('通信失敗');
                                }
                            });
			},
			'削除':function(){
                            var dragId = $(this).attr('title');
                            var fname = $('div[id="'+dragId+'"]').attr("data-fname");
                            var dname = $('div[id="'+dragId+'"]').attr("data-dirname");
                            if (confirm(dragId+'を削除しますか？')) {
                                $.ajax({
                                    url: "./Ajaxfuncs/ajaxtrash",
                                    type: "POST",
                                    data: {
                                        name : dragId,
                                        fname : fname,
                                        dname : dname
                                    },
                                    dataType: "text",
                                    success : function(response){
                                        //通信成功時の処理
                                        if(response=="not"){
                                            alert('該当のファイルが存在しません。');
                                        }else if(response=="direrr"){
                                            alert('削除に失敗しました。');
                                        }else if(response=="ok"){
                                            $('div[id="'+dragId+'"]').remove();
                                        }else{
                                            alert('削除に失敗しました。');
                                        }
                                    },
                                    error: function(){
                                        //通信失敗時の処理
                                        alert('通信失敗');
                                    }
                                });
                            } else {
                                //alert("キャンセルボタンがクリックされました");
                            }
			},
			'ダウンロード':function(){
                            $.colorbox({
                                href:"./Ajaxfuncs/ajaxdownfile/"+$(this).attr('title').replace('/','*'),
                                width:"0px",
                                height:"0px",
                                iframe:true
                            });
			},
		}
	});
        $(".filediv").unbind("click");
	$('.filediv').click(function() {
		var dragId = $(this).attr('id');
		var ext = dragId.split(".").reverse();
		ext_nm = ext[0];
		if(ext_nm.match(/(jpg|gif|png)/i)){
                    $.ajax({
                        url: "./Ajaxfuncs/ajaxopen",
                        type: "POST",
                        data: {
                            name : dragId
                        },
                        dataType: "text",
                        success : function(response){
                            if(response=="direrr"){
                                alert('ファイルを開けませんでした。');
                            }else if(response=='err1'){
                                alert('ファイルが存在しません。');
                            }else{
                                $.colorbox({
                                    href: response,
                                    maxHeight:"95%",
                                });
                                $(window).resize(function(){
                                    $.colorbox.resize({width:'90%', height:'90%'});
                                });

                            }
                        },
                        error: function(){
                            //通信失敗時の処理
                            alert('通信失敗');
                        }
                    });
		}else if(ext_nm.match(/(txt|js|css)/i)){
                    $.ajax({
                        url: "./Ajaxfuncs/ajaxopen",
                        type: "POST",
                        data: {
                            name : dragId
                        },
                        dataType: "text",
                        success : function(response){
                            if(response=="direrr"){
                                alert('ファイルを開けませんでした。');
                            }else if(response=='err1'){
                                alert('ファイルが存在しません。');
                            }else{
                                $.colorbox({
                                    href: response,
                                    width:"90%",
                                    height:"90%",
                                    iframe:true
                                });
                                $(window).resize(function(){
                                    $.colorbox.resize({width:'90%', height:'90%'});
                                });

                            }
                        },
                        error: function(){
                            //通信失敗時の処理
                            alert('通信失敗');
                        }
                    });
		}else{
                    alert("ファイル形式が対応しておりません。");
			
		}
	});
        $(".folderdiv").unbind("click");
	$('.folderdiv').click(function() {
            var dragId = $(this).attr('id');
            $.ajax({
                url: "./Filetops/openfol",
                type: "POST",
                data: {
                    id : dragId
                },
                dataType: "text",
                success : function(response){
                    if(response=="direrr"){
                        alert('フォルダを開けませんでした。');
                    }else if(response=="err"){
                        alert('該当のフォルダが存在しません');
                    }else{
                        $('div[id="op'+dragId+'"]').remove();
                        $("#dropzone").append('<div id="op'+dragId+'" data-fname="'+dragId+'" class="folopendiv ui-widget-content"><div class="fotitle">'+dragId+'<span id="close'+dragId+'" class="folclose"></span></div><div id="in'+dragId+'/" class="fobox">'+response+'</div></div>');
                        $('span[id="close'+dragId+'"]').click(function() {
                            $('div[id="op'+dragId+'"]').remove();
                        });
                        rcfunc();
                    }
                }
            });
	});
        $('.folopendiv').resizable({
            //autoHide: true
            maxHeight: '100%',
            maxWidth: '100%',
            minHeight: 150,
            minWidth: 150,
            ghost: true,
            animate: true,
            animateDuration: 'fast',
            animateEasing: 'easeInQuart'
        }).draggable({
            //cancel: '.inofdiv',
            scroll: false,
            containment: "#dropzone"
        });
        /*
        $('#dropzone').selectable({
            appendTo: "#dropzone",
            filter: '.filediv,.folderdiv',
            // 選択操作を開始したとき
            start: function(event, ui) {
            },
            // 選択操作を終了したとき
            stop: function(event, ui) {
            },
            // 選択が完了したとき
            selected: function( event, ui ) {
                // 選択された要素の値を取得
                console.log(ui.selected.id);
            }
        });
        */
        $(".filediv").draggable({
            appendTo: "#dropzone",
            revert: true,
            revertDuration : 0,
            helper: 'clone',
            scroll: false,
            zIndex: 1000,
            //containment: "#dropzone",
            opacity: 0.7
         });
        $(".folderdiv").draggable({
            appendTo: "#dropzone",
            revert: true,
            revertDuration : 0,
            helper: 'clone',
            scroll: false,
            zIndex: 1000,
            //containment: "#dropzone",
            opacity: 0.7
         });
	$( ".folderdiv" ).droppable({
		accept : ".outfdiv,.inofdiv" , // 受け入れる要素を指定
                hoverClass: 'folderdiv-hover',
                tolerance: 'pointer',
                greedy: true,
		drop : function(event , ui){
                    var dirid = this.id+'/';
                    if($(ui.draggable).hasClass('folderdiv')){
                        $.ajax({
                            url: "./Ajaxfuncs/ajaxmovetop",
                            type: "POST",
                            data: {
                                id : ui.draggable.attr("id"),
                                fname : ui.draggable.attr("data-fname"),
                                dname : ui.draggable.attr("data-dirname"),
                                dname2 : dirid,
                                ftype : 'fol'
                            },
                            dataType: "text",
                            success : function(response){
                                //通信成功時の処理
                                if(response=="not"){
                                    alert('該当のフォルダが存在しません。');
                                }else if(response=="direrr"){
                                    alert('フォルダの移動に失敗しました。');
                                }else if(response=="err"){
                                    alert('フォルダの移動に失敗しました。');
                                }else{
                                    ui.draggable.remove();
                                    folup2(dirid,response);
                                }
                            },
                            error: function(){
                                //通信失敗時の処理
                                alert('通信失敗');
                            }
                        });
                    }else if($(ui.draggable).hasClass('filediv')){
                        $.ajax({
                            url: "./Ajaxfuncs/ajaxmovetop",
                            type: "POST",
                            data: {
                                id : ui.draggable.attr("id"),
                                fname : ui.draggable.attr("data-fname"),
                                dname : ui.draggable.attr("data-dirname"),
                                dname2 : dirid,
                                ftype : 'file'
                            },
                            dataType: "text",
                            success : function(response){
                                //通信成功時の処理
                                if(response=="not"){
                                    alert('該当のファイルが存在しません。');
                                }else if(response=="direrr"){
                                    alert('ファイルの移動に失敗しました。');
                                }else if(response=="err"){
                                    alert('ファイルの移動に失敗しました。');
                                }else{
                                    ui.draggable.remove();
                                    dataup2(dirid,response);
                                }
                            },
                            error: function(){
                                //通信失敗時の処理
                                alert('通信失敗');
                            }
                        });
                    }else{

                    }
		},
		out : function (event , ui){
			
		}

	});
	$( ".folopendiv" ).droppable({
		accept : ".outfdiv,.inofdiv" , // 受け入れる要素を指定
                //hoverClass: 'foothover',
                tolerance: 'pointer',
                greedy: true,
		drop : function(event , ui){
                        if($(ui.draggable).hasClass('outfdiv')){
                                if($(this).attr("data-fname")==ui.draggable.attr("data-fname")){

                                }else{
                                    var dirid = $(this).attr("data-fname")+'/';
                                    if($(ui.draggable).hasClass('folderdiv')){
                                        $.ajax({
                                            url: "./Ajaxfuncs/ajaxmovetop",
                                            type: "POST",
                                            data: {
                                                id : ui.draggable.attr("id"),
                                                fname : ui.draggable.attr("data-fname"),
                                                dname : ui.draggable.attr("data-dirname"),
                                                dname2 : dirid,
                                                ftype : 'fol'
                                            },
                                            dataType: "text",
                                            success : function(response){
                                                //通信成功時の処理
                                                if(response=="not"){
                                                    alert('該当のフォルダが存在しません。');
                                                }else if(response=="direrr"){
                                                    alert('フォルダの移動に失敗しました。');
                                                }else if(response=="err"){
                                                    alert('フォルダの移動に失敗しました。');
                                                }else{
                                                    ui.draggable.remove();
                                                    folup2(dirid,response);
                                                }
                                            },
                                            error: function(){
                                                //通信失敗時の処理
                                                alert('通信失敗');
                                            }
                                        });
                                    }else if($(ui.draggable).hasClass('filediv')){
                                        $.ajax({
                                            url: "./Ajaxfuncs/ajaxmovetop",
                                            type: "POST",
                                            data: {
                                                id : ui.draggable.attr("id"),
                                                fname : ui.draggable.attr("data-fname"),
                                                dname : ui.draggable.attr("data-dirname"),
                                                dname2 : dirid,
                                                ftype : 'file'
                                            },
                                            dataType: "text",
                                            success : function(response){
                                                //通信成功時の処理
                                                if(response=="not"){
                                                    alert('該当のファイルが存在しません。');
                                                }else if(response=="direrr"){
                                                    alert('ファイルの移動に失敗しました。');
                                                }else if(response=="err"){
                                                    alert('ファイルの移動に失敗しました。');
                                                }else{
                                                    ui.draggable.remove();
                                                    dataup2(dirid,response);
                                                }
                                            },
                                            error: function(){
                                                //通信失敗時の処理
                                                alert('通信失敗');
                                            }
                                        });
                                    }else{

                                    }
                                }
                        }else if($(ui.draggable).hasClass('inofdiv')){
                                if((($(this).attr("data-fname")+'/')==ui.draggable.attr("data-dirname"))||(ui.draggable.attr("id")==$(this).attr("data-fname"))){

                                }else{
                                    var dirid = $(this).attr("data-fname")+'/';
                                    if($(ui.draggable).hasClass('folderdiv')){
                                        $.ajax({
                                            url: "./Ajaxfuncs/ajaxmovetop",
                                            type: "POST",
                                            data: {
                                                id : ui.draggable.attr("id"),
                                                fname : ui.draggable.attr("data-fname"),
                                                dname : ui.draggable.attr("data-dirname"),
                                                dname2 : dirid,
                                                ftype : 'fol'
                                            },
                                            dataType: "text",
                                            success : function(response){
                                                //通信成功時の処理
                                                if(response=="not"){
                                                    alert('該当のフォルダが存在しません。');
                                                }else if(response=="direrr"){
                                                    alert('フォルダの移動に失敗しました。');
                                                }else if(response=="err"){
                                                    alert('フォルダの移動に失敗しました。');
                                                }else{
                                                    ui.draggable.remove();
                                                    folup2(dirid,response);
                                                }
                                            },
                                            error: function(){
                                                //通信失敗時の処理
                                                alert('通信失敗');
                                            }
                                        });
                                    }else if($(ui.draggable).hasClass('filediv')){
                                        $.ajax({
                                            url: "./Ajaxfuncs/ajaxmovetop",
                                            type: "POST",
                                            data: {
                                                id : ui.draggable.attr("id"),
                                                fname : ui.draggable.attr("data-fname"),
                                                dname : ui.draggable.attr("data-dirname"),
                                                dname2 : dirid,
                                                ftype : 'file'
                                            },
                                            dataType: "text",
                                            success : function(response){
                                                //通信成功時の処理
                                                if(response=="not"){
                                                    alert('該当のファイルが存在しません。');
                                                }else if(response=="direrr"){
                                                    alert('ファイルの移動に失敗しました。');
                                                }else if(response=="err"){
                                                    alert('ファイルの移動に失敗しました。');
                                                }else{
                                                    ui.draggable.remove();
                                                    dataup2(dirid,response);
                                                }
                                            },
                                            error: function(){
                                                //通信失敗時の処理
                                                alert('通信失敗');
                                            }
                                        });
                                    }else{

                                    }
                                }
                        }
		},
		out : function (event , ui){
			
		}

	});
	$( "#dropzone" ).droppable({
		accept : ".inofdiv" , // 受け入れる要素を指定
                //hoverClass: 'foothover',
                tolerance: 'pointer',
                greedy: true,
		drop : function(event , ui){
			if($(ui.draggable).hasClass('folderdiv')){
                            $.ajax({
                                url: "./Ajaxfuncs/ajaxmovetop",
                                type: "POST",
                                data: {
                                    id : ui.draggable.attr("id"),
                                    fname : ui.draggable.attr("data-fname"),
                                    dname : ui.draggable.attr("data-dirname"),
                                    dname2 : '',
                                    ftype : 'fol'
                                },
                                dataType: "text",
                                success : function(response){
                                    //通信成功時の処理
                                    if(response=="not"){
                                        alert('該当のフォルダが存在しません。');
                                    }else if(response=="direrr"){
                                        alert('フォルダの移動に失敗しました。');
                                    }else if(response=="err"){
                                        alert('フォルダの移動に失敗しました。');
                                    }else{
                                        ui.draggable.remove();
                                        folup(response);
                                    }
                                },
                                error: function(){
                                    //通信失敗時の処理
                                    alert('通信失敗');
                                }
                            });
			}else if($(ui.draggable).hasClass('filediv')){
                            $.ajax({
                                url: "./Ajaxfuncs/ajaxmovetop",
                                type: "POST",
                                data: {
                                    id : ui.draggable.attr("id"),
                                    fname : ui.draggable.attr("data-fname"),
                                    dname : ui.draggable.attr("data-dirname"),
                                    dname2 : '',
                                    ftype : 'file'
                                },
                                dataType: "text",
                                success : function(response){
                                    //通信成功時の処理
                                    if(response=="not"){
                                        alert('該当のファイルが存在しません。');
                                    }else if(response=="direrr"){
                                        alert('ファイルの移動に失敗しました。');
                                    }else if(response=="err"){
                                        alert('ファイルの移動に失敗しました。');
                                    }else{
                                        ui.draggable.remove();
                                        dataup(response);
                                    }
                                },
                                error: function(){
                                    //通信失敗時の処理
                                    alert('通信失敗');
                                }
                            });
			}else{
				
			}
		},
		out : function (event , ui){
			
		}

	});
}
var readFileTree = function(itemEntry, fileCallback){	
	if(itemEntry.isFile){
		readFile(itemEntry, fileCallback);
	}else if(itemEntry.isDirectory){
		var dirReader = itemEntry.createReader();
		dirReader.readEntries(function(entries){
			var idx = entries.length;
			while(idx--){
				readFileTree(entries[idx], fileCallback);
			}	
		});
	}			
};
var readFile = function(fileEntry, callback) {
	//Get File object from FileEntry
	fileEntry.file(function(callback, file){
		if(callback){
			callback(file);
		}
	}.bind(this, callback));
};

var uploadFile = function(file){
    if(file && file instanceof File){
        var formData = new FormData();
        formData.append('file', file);

        $.ajax('./Ajaxfuncs/ajaxupfile', {
            method: 'POST',
            contentType: false,
            processData: false,
            data:formData,
            error: function(xhr, error) {
                console.log('アップロードに失敗しました');
                console.log(error);
            },
            success: function(response) {
                console.log('アップロードに成功しました');
                console.log(response);
                dataup(response);

            }
        });
    }
};

