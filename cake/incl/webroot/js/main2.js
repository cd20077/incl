var headflg = "on";

$(function(){
	
	//backset();
	//userimgset();
	
	$("#headbar").on("click", function(){
		//alert("OK");
		if(headflg=="on"){
			$("header").animate({'left':'-180px'});
			$("main").animate({'padding-left':'20px'});
			$("footer").animate({'padding-left':'20px'});
			$("footer").animate({'marigin-left':'-20px'});
			headflg="off";
		}else{
			$("header").animate({'left':'0px'});
			$("main").animate({'padding-left':'200px'});
			$("footer").animate({'padding-left':'200px'});
			$("footer").animate({'marigin-left':'-200px'});
			headflg="on";
		}
		
	});
	$('a.langon').click(function(){
		return false;
	})
});

function headimgset(headimg){
	//alert(backimg.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&'));
	$('#headimgid').css('background-image','url(../'+headimg.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&')+')');
	//$('article').animate({'background-image':'url('+backimg+')'});
	
}

function headimgset2(headimg){
	//alert(backimg.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&'));
	$('#headimgid').css('background-image','url(./'+headimg.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&')+')');
	//$('article').animate({'background-image':'url('+backimg+')'});
	
}

function dump(arr,level) {
   var dumped_text = "";
  if(!level) level = 0;
  
  //The padding given at the beginning of the line.
  var level_padding = "";
  for(var j=0; j < level + 1; j++) level_padding += "    ";
  if(typeof(arr) == 'object') { //Array/Hashes/Objects 
    for(var item in arr) {
      var value = arr[item];
      
      if(typeof(value) == 'object') { //If it is an array,
        dumped_text += level_padding + "'" + item + "' ...\n";
        dumped_text += dump(value,level+1);
      } else {
        dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
      }
    }
  } else { //Stings/Chars/Numbers etc.
    dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
  }
  return dumped_text;
}