
$(function(){
	
	$('.chart').easyPieChart({
		animate : 1000,
		lineWidth:15,
		size: 150,
		barColor: '#4F4',
		trackColor: '#444',
		scaleColor: '#666',
		lineCap: 'butt',
		onStep: function(from, to, percent) {
			$(this.el).find('.percent').text(Math.round(percent));
		}
	});
	
	$('.chart2').easyPieChart({
		animate : 1000,
		lineWidth:15,
		size: 120,
		barColor: '#4F4',
		trackColor: '#444',
		scaleColor: '#666',
		lineCap: 'butt',
		onStep: function(from, to, percent) {
			$(this.el).find('.percent2').text(Math.round(percent));
		}
	});
        
	$('#channame').click(function() {
		$('#channame').fadeOut('fast');
		$('#nameid').css( 'display', 'none');
		$('#nameid-edit').val( $( '#nameid').text()).fadeIn('fast').focus();
	});
	$('#nameid-edit').keypress( function ( e ) {
		if ( e.which == 13 ) {
			$('#nameid-edit').css( 'display', 'none');
			if($('#nameid-edit').val()==''){
				$('#channame').fadeIn('fast');
				$('#nameid').fadeIn('fast');
				alert('A user name is non-input.\nI canceled a change.');
			}else{
				$('#nameid').text($('#nameid-edit').val()).css( 'display', '');
				$.ajax({
					url: "../Ajaxfuncs/ajaxnamechan",
					type: "POST",
					data: { name : $( '#nameid').text()},
					dataType: "text",
					success : function(response){
						//通信成功時の処理
						if(response=="ok"){
							//alert(dragId);
							$('#channame').fadeIn('fast');
							$('#headusernameid').text($( '#nameid').text());
							alert('I changed a user name.');
						}else{
							alert('I failed in a change.');
						}
					},
					error: function(){
						//通信失敗時の処理
						alert('Communication failure');
					}
				});
				
			}
		}
	});
		
	$('#channame2').click(function() {
		$('#channame2').fadeOut('fast');
		$('#nameid2').css( 'display', 'none');
		$('#nameid2-edit').val( $( '#nameid2').text()).fadeIn('fast').focus();
	});
	$('#nameid2-edit').keypress( function ( e ) {
		if ( e.which == 13 ) {
			$('#nameid2-edit').css( 'display', 'none');
			if($('#nameid2-edit').val()==''){
				$('#channame2').fadeIn('fast');
				$('#nameid2').fadeIn('fast');
				alert('A project name is non-input.\nI canceled a change.');
			}else{
				$('#nameid2').css( 'display', '');
				$.ajax({
					url: "../../Ajaxfuncs/ajaxpronamechan",
					type: "POST",
					data: {
						name : $( '#nameid2-edit').val(),
						id : $( '#proid').val()
					},
					dataType: "text",
					success : function(response){
						//通信成功時の処理
						if(response=="ok"){
							//alert(dragId);
							$('#nameid2').text($('#nameid2-edit').val());
							$('#channame2').fadeIn('fast');
							alert('I changed a project name.');
						}else{
							$('#channame2').fadeIn('fast');
							alert('I failed in a change.');
						}
					},
					error: function(){
						//通信失敗時の処理
						$('#channame2').fadeIn('fast');
						alert('I failed in a change.');
					}
				});
				
			}
		}
	});
	/*
	$('#nameid-edit').blur(function() {
		$('#nameid-edit').css( 'display', 'none');
		if($('#nameid-edit').val()==''){
			$('#channame').fadeIn('fast');
			$('#nameid').fadeIn('fast');
			alert('ユーザー名が未入力です。\n変更を中止しました。');
		}else{
			$('#nameid').text($('#nameid-edit').val()).css( 'display', '');
			$.ajax({
				url: "../Ajaxfuncs/ajaxnamechan",
				type: "POST",
				data: { name : $( '#nameid').text()},
				dataType: "text",
				success : function(response){
					//通信成功時の処理
					if(response=="ok"){
						//alert(dragId);
						$('#channame').fadeIn('fast');
						$('#headusernameid').text($( '#nameid').text());
						alert('ユーザー名を変更しました。');
					}else{
						$('#channot').attr('id','channame');
						alert('I failed in a change.');
					}
				},
				error: function(){
					//通信失敗時の処理
					alert('通信失敗');
				}
			});
			
		}
	});
	*/
	$("#inputfile1").change( function() {
		var file = $('#inputfile1');
		var formData = new FormData();
		formData.append('file', file.prop("files")[0]);
		
		$.ajax('../Ajaxfuncs/ajaxupuserimg', {
		//$.ajax('./upload.php', {
			method: 'POST',
			contentType: false,
			processData: false,
			data:formData,
			dataType: "text",
			success: function(data) {
				topuserimgset(data);
				headimgset(data);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert( "I failed in the change of the user image." );
				console.log( textStatus );
				console.log( errorThrown );
			}
		});
	});
		
	$('#newproject').click(function() {
		if(0 == Number($('#atikt').text())){
                    alert('I can no longer make a project.');
		}else{
			$.colorbox({
                            html:'<ul id="css3form"><h2>New project</h2><li>Please input a project name.<br />(Less than 20 characters)</li><li><input type="text" class="fm" id="newproname" value="" maxlength="20" required /></li><p id="proname_err_mes" class="err_mes"></p><input type="button" id="newprobtn" class="btn" value="Make" /></ul>',
                            width:"500px",
                            height:"350px",
                            fixed: true
			});
			$('#newprobtn').click(function() {
				$('#proname_err_mes').html('');
				if($('#newproname').val()==''){
                                    $('#proname_err_mes').html('A project name is non-input.')
				}else{
					$.ajax({
						url: "../Ajaxfuncs/ajaxproadd",
						type: "POST",
						data: { name : $('#newproname').val()},
						dataType: "text",
						success : function(response){
                                                    //通信成功時の処理
                                                    if(response=="ok"){
                                                        $.ajax({
                                                            url: "../Users/groupadd",
                                                            type: "POST",
                                                            dataType: "text",
                                                            success : function(response){
                                                                var inpro = Number($('#inpronum').text()) + 1;
                                                                var adpro = Number($('#adpronum').text()) + 1;
                                                                var atikt = Number($('#atikt').text()) - 1;
                                                                $('#inpronum').text(inpro);
                                                                $('#adpronum').text(adpro);
                                                                $('#atikt').text(atikt);
                                                                $('#tabledivbox').html(response);
                                                                $.colorbox.close();
                                                            }
                                                        });
                                                    }else{
                                                        alert('I failed in the making of the project.');
                                                    }
						},
						error: function(){
                                                    //通信失敗時の処理
                                                    alert('Communication failure');
						}
					});
					
				}
				
			});
		}
	});
		
	$('#newmember').click(function() {
		if(0 == Number($('#atikt2').text())){
			alert('I can no longer add a member.');
		}else{
			$.colorbox({
				html:'<ul id="css3form"><h2>Project member addition</h2><span id="addcont"><li>Please input an e-mail address.</li><li><input type="text" class="fm" id="newmemmail" value="" required /></li><p id="promem_err_mes" class="err_mes"></p><input type="button" id="newmembtn" class="btn" value="Confirm a user" /></span></ul>',
				width:"500px",
				height:"350px",
				fixed: true
			});
			$('#newmembtn').click(function() {
				$('#promem_err_mes').html('');
				if($('#newmemmail').val()==''){
                                    $('#promem_err_mes').html('An e-mail address is non-input.')
				}else{
					var mailad = $('#newmemmail').val();
					$.ajax({
						url: "../../Ajaxfuncs/ajaxsearchumail",
						type: "POST",
						data: {
                                                    mail : mailad,
                                                    id : $('#proid').val()
						},
						dataType: "text",
						success : function(response){
                                                    //通信成功時の処理
                                                    if(response=="err1"){
                                                        $('#promem_err_mes').html('An applicable user is not found.');
                                                    }else if(response=="err2"){
                                                        $('#promem_err_mes').html('This user has been already joined.');
                                                    }else{
                                                        $('#addcont').html('<p>'+response+'</p><input type="button" id="newmemokbtn" class="btn" value="Add this user" />');
                                                        $('#newmemokbtn').click(function() {
                                                            $.ajax({
                                                                url: "../../Ajaxfuncs/ajaxaddmem",
                                                                type: "POST",
                                                                data: {
                                                                    mail : mailad,
                                                                    id : $('#proid').val()
                                                                },
                                                                dataType: "text",
                                                                success : function(response){
                                                                    if(response=="err"){
                                                                        $.colorbox.close();
                                                                        alert('Failed in addition.');
                                                                    }else if(response=="ok"){
                                                                        $.colorbox.close();
                                                                        var atikt2 = Number($('#atikt2').text()) - 1;
                                                                        $('#atikt2').text(atikt2);
                                                                        addmember();
                                                                        $.colorbox.close();
                                                                    }
                                                                }
                                                            });
                                                        });
                                                    }
						},
						error: function(){
                                                    //通信失敗時の処理
                                                    alert('Communication failure');
						}
					});
					
				}
				
			});
		}
	});
		
});
    
function topbackset(backimg){
	$('#userart').css('background-image','url(../'+backimg.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&')+')');
	
}

function topuserimgset(userimg){
	$('#userimgid').css('background-image','url(../'+userimg.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&')+')');
	
}

function memdel(id,name){
	if (confirm('Do you let you withdraw from '+name+' ？')) {
		$.ajax({
			url: "../../Ajaxfuncs/ajaxmemdel",
			type: "POST",
			data: {
				name : name,
				memid : id,
				gid : $('#proid').val()
			},
			dataType: "text",
			success : function(response){
				if(response=="err"){
					$.colorbox.close();
					alert('I failed in deletion.');
				}else if(response=="ok"){
					$.colorbox.close();
					var atikt2 = Number($('#atikt2').text()) + 1;
					$('#atikt2').text(atikt2);
					addmember();
					$.colorbox.close();
				}
			}
		});
	}else{
		
	}
}
function chanauth(id,name,authid){
    if (confirm('Do you change authority of '+name+' ？')) {
        $.ajax({
            url: "../../Ajaxfuncs/ajaxchanauth",
            type: "POST",
            data: {
                name : name,
                memid : id,
                authid : authid,
                gid : $('#proid').val()
            },
            dataType: "text",
            success : function(response){
                if(response=="err"){
                    $.colorbox.close();
                    alert('Failed in a change of the authority.');
                }else if(response=="ok"){
                    $.colorbox.close();
                    addmember();
                    $.colorbox.close();
                }
            }
        });
    }else{
		
	}
}
function addmember(){
    $.ajax({
        url: "../../GroupMembers/addmember",
        type: "POST",
        data: {
            id : $('#proid').val()
        },
        dataType: "text",
        success : function(response){
            if(response=="err"){

            }else{
                $('#tabledivbox2').html(response);
            }
        }
    });
}
