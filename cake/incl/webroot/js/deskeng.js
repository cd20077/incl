
$(function(){
	$('#dropzone').haloContext({
            bindings:{
                'Config':function(){
                    location.href = './users/usertop';
                },
                'Project':function(){
                    location.href = './users/grouptop';
                },
                'Upload':function(){
                    upfunc();
                },
                'NewFolder':function(){
                    folapp();
                },
            }
	});
	
	rcfunc();
        
	$( "#trashzone" ).droppable({
		accept : ".filediv,.folderdiv" ,
                hoverClass: 'foothover',
                tolerance: 'pointer',
                greedy: true,
		drop : function(event , ui){
			if($(ui.draggable).hasClass('folderdiv')){
                            var dragId = ui.draggable.attr("id");
                            if (confirm('Do you delete '+dragId+' ？')) {
                                $.ajax({
                                    url: "./Ajaxfuncs/ajaxfoltrash",
                                    type: "POST",
                                    data: {name : dragId},
                                    dataType: "text",
                                    success : function(response){
                                        if(response=="not"){
                                            alert('An applicable folder does not exist.');
                                        }else if(response=="direrr"){
                                            alert('Failed in deletion.');
                                        }else if(response=="ok"){
                                            ui.draggable.remove();
                                        }else{
                                            alert('Failed in deletion.');
                                        }
                                    },
                                    error: function(){
                                        //Communication failure時の処理
                                        alert('Communication failure');
                                    }
                                });
                            } else {
                                //alert("キャンセルボタンがクリックされました");
                            }
			}else if($(ui.draggable).hasClass('filediv')){
                            var dragId = ui.draggable.attr("id");
                            var fname = ui.draggable.attr("data-fname");
                            var dname = ui.draggable.attr("data-dirname");
                            if (confirm('Do you delete '+dragId+' ？')) {
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
                                            alert('An applicable file does not exist.');
                                        }else if(response=="direrr"){
                                            alert('Failed in deletion.');
                                        }else if(response=="ok"){
                                            //alert(dragId);
                                            ui.draggable.remove();
                                        }else{
                                            alert('Failed in deletion.');
                                        }
                                    },
                                    error: function(){
                                        //Communication failure時の処理
                                        alert('Communication failure');
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
		accept : ".filediv" ,
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
                        alert("Can set only jpg,gif,png file in the background.");
                    }else{
                        if (confirm('Do you change background？')) {
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
                                        alert('An applicable file does not exist.');
                                    }else if(response=="direrr"){
                                        alert('Failed in the change of the background.');
                                    }else if(response=='err'){
                                        alert('Failed in the change of the background.');
                                    }else{
                                        backset(response);
                                    }
                                },
                                error: function(){
                                    //Communication failure時の処理
                                    alert('Communication failure');
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
                    alert('Failed in the change of the background.');
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
        html:'<ul id="css3form"><h2>New Folder</h2><span id="addcont"><li>Please input a folder name.</li><li><input type="text" class="fm" id="newfolname" value="" required /></li><p id="promem_err_mes" class="err_mes"></p><input type="button" id="newfolbtn" class="btn" value="Make" /></span></ul>',
        width:"500px",
        height:"350px",
        fixed: true
    });
    $('#newfolbtn').click(function() {
        $('#promem_err_mes').html('');
        var str = $('#newfolname').val();
        if(str==''){
            $('#promem_err_mes').html('A folder name is non-input.');
        }else if( str.match( /^.*[(\\|\"|/|:|\*|?|<|>|\|)].*$/)){
            $('#promem_err_mes').html(' / : * ? &quot; &lt; &gt; | is not usable for a folder name.');
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
                        alert('Failed in the making of the folder.');
                    }else if(response=="err"){
                        $.colorbox.close();
                        alert('Failed in the making of the folder.');
                    }else{
                        $.colorbox.close();
                        folup(response);
                    }
                },
                error: function(){
                    //Communication failure時の処理
                    alert('Communication failure');
                }
            });
        }

    });
}

function upfunc(){
    $.colorbox({
        html:'<ul id="css3form"><h2>Upload</h2><span id="addcont"><li>I can upload even drag & drop.</li><input type="button" id="newfolbtn" class="btn" value="Choose a file." onClick="$(\'#fileup2\').click();" /></span><input type="file" id="fileup2" style="display:none;" multiple /></ul>',
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
			'Open':function(){
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
                                        alert('Did not open a folder.');
                                    }else if(response=="err"){
                                        alert('An applicable folder does not exist.');
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
			'ReName':function(){
				var dragId = $(this).attr('title');
				var fname = $('div[id="'+dragId+'"]').attr("data-fname");
				var dname = $('div[id="'+dragId+'"]').attr("data-dirname");
				$.colorbox({
                                    html:'<ul id="css3form"><h2>ReName</h2><span id="addcont"><li>Please input a new folder name.</li><li>Now：'+fname+'</li><li><input type="text" class="fm" id="refolname" maxlength="50" value="'+fname+'" required /></li><p id="promem_err_mes" class="err_mes"></p><input type="button" id="refolbtn" class="btn" value="RENAME" /></span></ul>',
                                    width:"500px",
                                    height:"400px",
                                    fixed: true
				});
				$('#refolbtn').click(function() {
                                    $('#promem_err_mes').html('');
                                    var str = $('#refolname').val();
                                    if(str==''){
                                        $('#promem_err_mes').html('A folder name is non-input.');
                                    }else if( str.match( /^.*[(\\|\"|/|:|\*|?|<|>|\|)].*$/)){
                                        $('#promem_err_mes').html('/ : * ? &quot; &lt; &gt; | is not usable for a folder name.');
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
                                                    alert('An applicable folder does not exist.');
                                                }else if(response=="direrr"){
                                                    $.colorbox.close();
                                                    alert('Failed in the change of the folder name.');
                                                }else if(response=="err"){
                                                    $.colorbox.close();
                                                    alert('Failed in the change of the folder name.');
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
                                                //Communication failure時の処理
                                                alert('Communication failure');
                                            }
                                        });
                                    }
				});
			},
			'Copy':function(){
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
                                        alert('An applicable folder does not exist.');
                                    }else if(response=="direrr"){
                                        alert('Failed in the copy of the folder.');
                                    }else if(response=="err"){
                                        alert('Failed in the copy of the folder.');
                                    }else{
                                        if(dname==''){
                                            folup(response);
                                        }else{
                                            folup2(dname,response);
                                        }
                                    }
                                },
                                error: function(){
                                    //Communication failure時の処理
                                    alert('Communication failure');
                                }
                            });
			},
			'Delete':function(){
                            var dragId = $(this).attr('title');
                            if (confirm('Do you delete the '+dragId+' ？')) {
                                $.ajax({
                                    url: "./Ajaxfuncs/ajaxfoltrash",
                                    type: "POST",
                                    data: { name : dragId},
                                    dataType: "text",
                                    success : function(response){
                                        //通信成功時の処理
                                        if(response=="not"){
                                            alert('An applicable folder does not exist.');
                                        }else if(response=="direrr"){
                                            alert('Failed in deletion.');
                                        }else if(response=="ok"){
                                            $('div[id="'+dragId+'"]').remove();
                                        }else{
                                            alert('Failed in deletion.');
                                        }
                                    },
                                    error: function(){
                                        //Communication failure時の処理
                                        alert('Communication failure');
                                    }
                                });
                            } else {
                                //alert("キャンセルボタンがクリックされました");
                            }
			},
			'Download':function(){
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
			'Open':function(){
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
                                                alert('Did not open a file.');
                                            }else if(response=='err1'){
                                                alert('A file does not exist.');
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
                                            //Communication failure時の処理
                                            alert('Communication failure');
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
                                                alert('Did not open a file.');
                                            }else if(response=='err1'){
                                                alert('A file does not exist.');
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
                                            //Communication failure時の処理
                                            alert('Communication failure');
                                        }
                                    });
				}else{
					alert("File format does not support.");
					
				}
			},
			'ReName':function(){
                            var dragId = $(this).attr('title');
                            var fname = $('div[id="'+dragId+'"]').attr("data-fname");
                            var dname = $('div[id="'+dragId+'"]').attr("data-dirname");
                            var ext = fname.match(/(.+)(\.[^.]+$)/)[1];
                            $.colorbox({
                                html:'<ul id="css3form"><h2>ReName</h2><span id="addcont"><li>Please input a new file name.</li><li>(The extension does not include it. To up to 50 characters.)</li><li>Now：'+fname+'</li><li><input type="text" class="fm" maxlength="50" id="refilename" value="'+ext+'" required /></li><p id="promem_err_mes" class="err_mes"></p><input type="button" id="refilebtn" class="btn" value="RENAME" /></span></ul>',
                                width:"500px",
                                height:"400px",
                                fixed: true
                            });
                            $('#refilebtn').click(function() {
                                $('#promem_err_mes').html('');
                                var str = $('#refilename').val();
                                if(str==''){
                                    $('#promem_err_mes').html('A file name is non-input.');
                                }else if( str.match( /^.*[(\\|\"|/|:|\*|?|<|>|\|)].*$/)){
                                    $('#promem_err_mes').html('/ : * ? &quot; &lt; &gt; | is not usable for a file name.');
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
                                                alert('An applicable file does not exist.');
                                            }else if(response=="direrr"){
                                                $.colorbox.close();
                                                alert('Failed in the change of the file name.');
                                            }else if(response=="err"){
                                                $.colorbox.close();
                                                alert('Failed in the change of the file name.');
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
                                            //Communication failure時の処理
                                            alert('Communication failure');
                                        }
                                    });
                                }

                            });
			},
			'Copy':function(){
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
                                        alert('An applicable file does not exist.');
                                    }else if(response=="direrr"){
                                        alert('Failed in the copy of the file.');
                                    }else if(response=="err"){
                                        alert('Failed in the copy of the file.');
                                    }else{
                                        if(dname==''){
                                            dataup(response);
                                        }else{
                                            dataup2(dname,response);
                                        }
                                    }
                                },
                                error: function(){
                                    //Communication failure時の処理
                                    alert('Communication failure');
                                }
                            });
			},
			'Delete':function(){
                            var dragId = $(this).attr('title');
                            var fname = $('div[id="'+dragId+'"]').attr("data-fname");
                            var dname = $('div[id="'+dragId+'"]').attr("data-dirname");
                            if (confirm('Do you delete '+dragId+' ？')) {
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
                                            alert('An applicable file does not exist.');
                                        }else if(response=="direrr"){
                                            alert('Failed in deletion.');
                                        }else if(response=="ok"){
                                            $('div[id="'+dragId+'"]').remove();
                                        }else{
                                            alert('Failed in deletion.');
                                        }
                                    },
                                    error: function(){
                                        //Communication failure時の処理
                                        alert('Communication failure');
                                    }
                                });
                            } else {
                                //alert("キャンセルボタンがクリックされました");
                            }
			},
			'Download':function(){
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
                                alert('Did not open a file.');
                            }else if(response=='err1'){
                                alert('A file does not exist.');
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
                            //Communication failure時の処理
                            alert('Communication failure');
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
                                alert('Did not open a file.');
                            }else if(response=='err1'){
                                alert('A file does not exist.');
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
                            //Communication failure時の処理
                            alert('Communication failure');
                        }
                    });
		}else{
                    alert("File format does not support.");
			
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
                        alert('Did not open a folder.');
                    }else if(response=="err"){
                        alert('An applicable folder does not exist');
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
                                    alert('An applicable folder does not exist.');
                                }else if(response=="direrr"){
                                    alert('Failed in the movement of the folder.');
                                }else if(response=="err"){
                                    alert('Failed in the movement of the folder.');
                                }else{
                                    ui.draggable.remove();
                                    folup2(dirid,response);
                                }
                            },
                            error: function(){
                                //Communication failure時の処理
                                alert('Communication failure');
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
                                    alert('An applicable file does not exist.');
                                }else if(response=="direrr"){
                                    alert('Failed in the movement of the file.');
                                }else if(response=="err"){
                                    alert('Failed in the movement of the file.');
                                }else{
                                    ui.draggable.remove();
                                    dataup2(dirid,response);
                                }
                            },
                            error: function(){
                                //Communication failure時の処理
                                alert('Communication failure');
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
                                                    alert('An applicable folder does not exist.');
                                                }else if(response=="direrr"){
                                                    alert('Failed in the movement of the folder.');
                                                }else if(response=="err"){
                                                    alert('Failed in the movement of the folder.');
                                                }else{
                                                    ui.draggable.remove();
                                                    folup2(dirid,response);
                                                }
                                            },
                                            error: function(){
                                                //Communication failure時の処理
                                                alert('Communication failure');
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
                                                    alert('An applicable file does not exist.');
                                                }else if(response=="direrr"){
                                                    alert('Failed in the movement of the file.');
                                                }else if(response=="err"){
                                                    alert('Failed in the movement of the file.');
                                                }else{
                                                    ui.draggable.remove();
                                                    dataup2(dirid,response);
                                                }
                                            },
                                            error: function(){
                                                //Communication failure時の処理
                                                alert('Communication failure');
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
                                                    alert('An applicable folder does not exist.');
                                                }else if(response=="direrr"){
                                                    alert('Failed in the movement of the folder.');
                                                }else if(response=="err"){
                                                    alert('Failed in the movement of the folder.');
                                                }else{
                                                    ui.draggable.remove();
                                                    folup2(dirid,response);
                                                }
                                            },
                                            error: function(){
                                                //Communication failure時の処理
                                                alert('Communication failure');
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
                                                    alert('An applicable file does not exist.');
                                                }else if(response=="direrr"){
                                                    alert('Failed in the movement of the file.');
                                                }else if(response=="err"){
                                                    alert('Failed in the movement of the file.');
                                                }else{
                                                    ui.draggable.remove();
                                                    dataup2(dirid,response);
                                                }
                                            },
                                            error: function(){
                                                //Communication failure時の処理
                                                alert('Communication failure');
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
                                        alert('An applicable folder does not exist.');
                                    }else if(response=="direrr"){
                                        alert('Failed in the movement of the folder.');
                                    }else if(response=="err"){
                                        alert('Failed in the movement of the folder.');
                                    }else{
                                        ui.draggable.remove();
                                        folup(response);
                                    }
                                },
                                error: function(){
                                    //Communication failure時の処理
                                    alert('Communication failure');
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
                                        alert('An applicable file does not exist.');
                                    }else if(response=="direrr"){
                                        alert('Failed in the movement of the file.');
                                    }else if(response=="err"){
                                        alert('Failed in the movement of the file.');
                                    }else{
                                        ui.draggable.remove();
                                        dataup(response);
                                    }
                                },
                                error: function(){
                                    //Communication failure時の処理
                                    alert('Communication failure');
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
            },
            success: function(response) {
                dataup(response);

            }
        });
    }
};

