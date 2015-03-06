/*
 * スマートフォン向け 簡易チャット
 * 
 */
var loglasttime='0001-01-01 00:00:00';
var onflg='';

$(document).ready(function(){
    $(".chatopen").colorbox({
        inline:true,
        width:'100%',
        maxWidth: '800',
        height:'90%',
        onOpen:function(){
            onflg='on';
            $( '.b2 a').css('-webkit-animation','');
        },
        onClosed:function(){
            onflg='off';
            
        }
    });
    /*
    $(window).resize(function(){
        $(".chatopen").colorbox.resize({
            width:'100%',
            maxWidth: '800',
            height:'90%'
       });
    });
    `*/
  logAll(); //ログをロード
   
   $("#str").focus(function(){ 
      scTarget(); 
   });
      
/*
 * 名前送信
   $("#button2").click(function(){ 
      if( !$("#name").val() ) return false;
      
    //HTMLタグを除去
      var name = $('<div>'+$("#name").val()+'</div>').text();
      
      writeLog(name+' : 入室しました','roomin');

      $("#chatwrap").show(); //チャット枠を表示
      $("#f3").hide(); //名前枠を非表示
   });
 */
   
/*
 * チャット送信
 */
   $("#button1").click(function(){ 
      var str = $("#str").val();
      if(str){
         writeLog(str,null); 
      }else{
         //名前無いとき *エラー表示など
      }
   });
   
    $('#str').keypress( function ( e ) {
        if ( e.which == 13 ) {
            var str = $("#str").val();
            if(str){
               writeLog(str,null); 
            }else{
               //名前無いとき *エラー表示など
            }
        }
    });
   
 /*
  * チャットを記録する
  */
    function writeLog(str,type){
        $("#str").val('');
         $.ajax({
            type: "POST",
            url: "../../GroupMembers/cometfunc",
            data: {
                type:type,
                str:str,
                gid : $( '#proid').val()
            },
            success: function(xml){
               $(xml).find("item").each(function(){
                  loadLog();
                  scTarget();

               });
            }
         }); 
    }
   
 /*
  * ログをリロードする
  */
   function logAll(flag){
      loadLog();
      setTimeout(function(){
         logAll();
      },5000); //リロード時間はここで調整
   }
   
 /*
  * 全ログを表示
  */
   function loadLog(){
    $.ajax({
       async: false,
       type: "POST",
       url: "../../GroupMembers/cometfunc",
       data: {
           type:'log',
           gid : $( '#proid').val(),
           llt : loglasttime
       },
       success: function(xml){
           if(xml == 'none'){
               
           }else{
               if(onflg=='off'){
                   $( '.b2 a').css('-webkit-animation','bg-color alternate 1s infinite');
               }else{
                   
               }
               $('#Log ul li').remove(); //一旦空にする
               $(xml).find("item").each(function(){
                   var chat  = $(this).find("log").text();
                   var name  = $(this).find("name").text();
                   var date = $(this).find("date").text();
                   loglasttime = date;
                   $("<li></li>").html('<li style="color:black">'+name+' <div class="log">'+chat+'</div> <div class="date">'+date+'</div>').appendTo("#Log ul");
               });
               scTarget();
           }
       }
    }); 
   }

/*
 * 画面を最下部へ移動させる
 */
  function scTarget(){
     $("#Log").animate({ 
        scrollTop:$("#Log")[0].scrollHeight
     }, 0, "swing"); //swingで0が良さそう
        return false;
  }
   
});