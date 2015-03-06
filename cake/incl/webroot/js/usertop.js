
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
							alert('変更に失敗しました。');
						}
					},
					error: function(){
						//通信失敗時の処理
						alert('通信失敗');
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
				alert('プロジェクト名が未入力です。\n変更を中止しました。');
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
							alert('プロジェクト名を変更しました。');
						}else{
							$('#channame2').fadeIn('fast');
							alert('変更に失敗しました。');
						}
					},
					error: function(){
						//通信失敗時の処理
						$('#channame2').fadeIn('fast');
						alert('変更に失敗しました。');
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
						alert('変更に失敗しました。');
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
				//alert( data);
				//$('#userimgid').attr('src','../'+data);
				topuserimgset(data);
				headimgset(data);
				//console.log(data);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert( "ユーザーイメージの変更に失敗しました。" );
				console.log( textStatus );
				console.log( errorThrown );
			}
		});
	});
		
	$('#newproject').click(function() {
		if(0 == Number($('#atikt').text())){
			alert('これ以上プロジェクトを作成できません。');
		}else{
			$.colorbox({
				/*
				href:"../GroupLists/groupadd",
				iframe:true,
				*/
				html:'<ul id="css3form"><h2>新規プロジェクト</h2><li>プロジェクト名を入力してください。(20文字以内)</li><li><input type="text" class="fm" id="newproname" value="" maxlength="20" required /></li><p id="proname_err_mes" class="err_mes"></p><input type="button" id="newprobtn" class="btn" value="作成する" /></ul>',
				width:"500px",
				height:"350px",
				fixed: true
			});
			$('#newprobtn').click(function() {
				$('#proname_err_mes').html('');
				if($('#newproname').val()==''){
					$('#proname_err_mes').html('プロジェクト名が未入力です。')
				}else{
					//alert($('#newproname').val());
					$.ajax({
						url: "../Ajaxfuncs/ajaxproadd",
						type: "POST",
						data: { name : $('#newproname').val()},
						dataType: "text",
						success : function(response){
							//通信成功時の処理
							if(response=="ok"){
								//alert(dragId);
								//$('#protbody').append('<tr></tr>');
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
								
								//alert('プロジェクトを作成しました。');
							}else{
								alert('プロジェクトの作成に失敗しました。');
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
	});
		
	$('#newmember').click(function() {
		if(0 == Number($('#atikt2').text())){
			alert('これ以上メンバーを追加できません。');
		}else{
			$.colorbox({
				/*
				href:"../GroupLists/groupadd",
				iframe:true,
				*/
				html:'<ul id="css3form"><h2>プロジェクトメンバー追加</h2><span id="addcont"><li>メールアドレスを入力してください。</li><li><input type="text" class="fm" id="newmemmail" value="" required /></li><p id="promem_err_mes" class="err_mes"></p><input type="button" id="newmembtn" class="btn" value="ユーザーを確認する" /></span></ul>',
				width:"500px",
				height:"350px",
				fixed: true
			});
			$('#newmembtn').click(function() {
				$('#promem_err_mes').html('');
				if($('#newmemmail').val()==''){
					$('#promem_err_mes').html('メールアドレスが未入力です。')
				}else{
					//alert($('#newproname').val());
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
								$('#promem_err_mes').html('該当ユーザーが見つかりません。');
							}else if(response=="err2"){
								$('#promem_err_mes').html('該当ユーザーは既に加入済みです。');
							}else{
								$('#addcont').html('<p>'+response+'</p><input type="button" id="newmemokbtn" class="btn" value="このユーザーを追加する" />');
								$('#newmemokbtn').click(function() {
									//alert(mailad);
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
												alert('追加に失敗しました。');
											}else if(response=="ok"){
												$.colorbox.close();
												var atikt2 = Number($('#atikt2').text()) - 1;
												$('#atikt2').text(atikt2);
												addmember();
												$.colorbox.close();
											}
										}
									});
									/*
									*/
									//alert('プロジェクトを作成しました。');
								});
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
	});
		
});
    
function topbackset(backimg){
	$('#userart').css('background-image','url(../'+backimg.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&')+')');
	
}

function topuserimgset(userimg){
	$('#userimgid').css('background-image','url(../'+userimg.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&')+')');
	
}

function memdel(id,name){
	if (confirm(name+' を脱退させますか？')) {
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
					alert('削除に失敗しました。');
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
	if (confirm(name+' の権限を変更しますか？')) {
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
					alert('権限の変更に失敗しました。');
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
