var data = new Array();
var cnt = 0;
var ctx = null;
var canvas = null;
var dotX = new Array(1);
var dotY = new Array(1);
var max1 = 0;
var max2 = 0;
var memoriMax1 = 0;
var color = "";

$(function(){
	/*
	$.ajax({
		url: "data.xml",
		type: "GET",
		dataType: "xml",
		success: readData
	});
	$.ajax({
        type: 'GET',
        url: "./auths/login",
        dataType: "html",
        cache: false,
        success: function(data, status) {
            $('.popup').empty().append(data);
            popupForm();
        }
    });
	*/
	var scrolly = 2000;
	var speed = 400;
	$('html').mousewheel(function(event, mov) {
		if(jQuery.browser.webkit){
			if (mov > 0) scrolly =  $('body').scrollTop() - speed;
			else if (mov < 0) scrolly =  $('body').scrollTop() + speed;
		} else {
			if (mov > 0) scrolly =  $('html').scrollTop() - speed;
			else if (mov < 0) scrolly =  $('html').scrollTop() + speed;
		}
		$('html,body')
			.stop()
			.animate({scrollTop: scrolly}, 'slow',$.easie(0,0,0,1));
			//イージングプラグイン使わない場合
			//.animate({ scrollTop: scrolly }, 'normal');
		return false;
	});
	
	$(".loga").colorbox({
            iframe:true,
            width:'600px',
            height:'450px',
            fixed: true
        });
	
});
 
function entrylink(id){
    $.colorbox({
        href:'../../users/entry_conf/'+id,
        width:'600px',
        height:'450px',
        iframe:true,
		fixed: true
    });
}
 
function popupForm()
{
	$('.popup form').ajaxForm(function(res) {
		$('.popup').empty().append(res);
		popupForm();
	});
}