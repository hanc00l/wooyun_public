<?php 
require('conn.php');
include "comment.class.php";
@$chk_count = mysql_fetch_array($chk_count0);
@$wybug_id0=isset($_GET['wybug_id'])?$_GET['wybug_id']:'WooYun-2016-222083';
//@$wybug_id = str_replace("'","",$wybug_id0);
@$wybug_id=addslashes($wybug_id0);
@$bugs = mysql_query("select * from bugs where wybug_id='".$wybug_id."'");
@$bug_detail=mysql_fetch_array($bugs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="x-ua-compatible" content="ie=7"/>
<title> <?php echo $bug_detail['wybug_title'].' | '.$bug_detail['wybug_id'];?>| WooYun.org </title>
<meta name="author" content="80sec"/>
<meta name="copyright" content="http://www.wooyun.org/"/>
<meta name="keywords" content="乌云官方漏洞,路人甲,未授权访问/权限绕过,wooyun,应用安全,web安全,系统安全,网络安全,漏洞公布,漏洞报告,安全资讯。"/>
<meta name="description" content="|WooYun是一个位于厂商和安全研究者之间的漏洞报告平台,注重尊重,进步,与意义"/>
<link rel="icon" href="/favicon.ico" sizes="32x32"/>
<link href="css/style_1.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery-1.4.2.min_1.js" type="text/javascript"></script>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="script.js"></script>
</head>
<body id="bugDetail">
<style>#myBugListTab{position:relative;display:inline;border:none}#myBugList{position:absolute;display:none;margin-left:309px;* margin-left:-60px; * margin-top:18px ; border:#c0c0c0 1px solid; padding:2px 7px; background:#FFF }#myBugList li{text-align:left}</style>
<script type="text/javascript">
$(document).ready(function(){

	if ( $("#__cz_push_d_object_box__") ) {
		$("script[src^='']").attr("src"," ").remove();
		$("#__cz_push_d_object_box__").empty().remove();
		$("a[id^='__czUnion_a']").attr("href","#").remove();
	}

	if ( $("#ooDiv") ) {
		$("#ooDiv").empty().parent("div").remove();
	}

	$("#myBugListTab").toggle(
		function(){
			$("#myBugList").css("display","block");
		},
		function(){
			$("#myBugList").css("display","none");
		}
	);

	if ( $(window).scrollTop() > 120 ) {
		$("#back-to-top").fadeIn(300);
	} else {
		$("#back-to-top").fadeOut(300);
	} 

	$(window).scroll(function(){
		if ( $(window).scrollTop() > 120 ) {
			$("#back-to-top").fadeIn(300);
		} else {
			$("#back-to-top").fadeOut(300);
		} 
	});

	$("#back-to-top a").click(function() {
		$('body,html').animate({scrollTop:0},300);
		return false;
	});

	$("#go-to-comment a").click(function() {
		var t = $("#replys").offset().top - 52;
		$('body,html').animate({scrollTop:t},300);
		return false;
	});

});

function gofeedback(){
	var bugid=$("#fbid").val();
	if(bugid){
		var url="/feedback.php?wybug_id=" + "<?php echo $bug_detail['wybug_id'];?>";
	}else{
		var url="/feedback.php"
	}
	window.open(url);
}
</script>
<div class="go-to-wrapper">
<ul class="go-to">
<li id="go-to-comment" title="转到评论"><a href="#">转到评论</a></li>
<li id="go-to-feedback" title="我要反馈"><a href="javascript:void(0)" onclick="gofeedback()">我要反馈</a></li>
<li id="back-to-top" title="回到顶部"><a href="#">回到顶部</a></li>
</ul>
</div>
<div class="banner">
<div class="logo">
<h1>WooYun.org</h1>
<div class="weibo"><iframe width="136" height="24" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0" scrolling="no" border="0" src="http://widget.weibo.com/relationship/followbutton.php?language=zh_cn&width=136&height=24&uid=1981622273&style=2&btn=red&dpc=1"></iframe>
</div>
<div class="wxewm">
<a class="ewmthumb" href="javascript:void(0)"><span><img src="picture/ewm.jpg"width="220" border="0"></span><img src="picture/weixin_30.png"width="22" border="0"></a>
		</div>
		</div>
		
				<div class="login">
									<?php 				   if(isset($_SESSION['username'])){
					 if(@$chk_count['is_fromwooyun'] == 1){
						 echo '<p style="padding:5px 10px 0 0">欢迎白帽子 <a href="whitehat_detail.php?whitehat='.$_SESSION['username'].'">'.$_SESSION['username'].'</a> | <a href="logout.php">注销</a></p>';
					 }
					 else{
					 echo  '<p style="padding:5px 10px 0 0">欢迎 '.$_SESSION['username'].' | <a href="logout.php">注销</a></p>';
					 }
                  }else{
					  echo '<a href="user.php">登录</a> | <a class="reg">注册</a>';
				  }
				?>
				</div>
				</div>

	<div class="nav" id="nav_sc">
				<ul>
			<li><a href="index.php">首页</a></li>
			<li><a href="corps.php">厂商列表</a></li>
			<li><a href="whitehats.php">白帽子</a></li>
			<li><a>乌云榜</a></li> 
            <li><a>团队</a></li>
            <li><a href="bugs.php">漏洞列表</a></li>
			<li class="new"><a>提交漏洞</a></li>
			<li><a href="../index.php" style="color:rgb(246,172,110);font-size:14px;font-weight:blod">社区</a></li>
			<li><a>公告</a></li>
		</ul>
        <form action="searchbug.php" method="get" id="searchbox">
	    <input type="checkbox" name="detail" id="detail"> 搜索漏洞详情
            <input type="text" name="q" id="search_input" />
            <input type="submit" value="搜索" id="search_button" />
        </form>
	</div>

	<div class="bread" style="padding-top: 4px;">
		<div style="float:left">当前位置：<a href="index.php">WooYun</a> >> <a href="#">漏洞信息</a></div>
	</div>

<div class="content">
<input type="hidden" id="token" style="display:none" value="" />
		<h2>漏洞概要
		<span style="margin:0 0 0 580px; float:right; position:absolute; font-size:14px; font-weight:normal">关注数(<span id="attention_num">24</span>)
		<span id="attend_action">
		<a class="btn" href="javascript:void(0)" onclick="AttendBug(222083)">关注此漏洞</a></span>
		</span></h2>
		<h3>缺陷编号：<a href="bug_detail.php?wybug_id=<?php echo $bug_detail['wybug_id'].'">'.$bug_detail['wybug_id'];?></a>
		<input id="fbid" type="hidden" value="222083"></h3>
		<h3 class='wybug_title'>漏洞标题：<?php echo $bug_detail['wybug_title'];?></h3>
		<h3 class='wybug_corp'>相关厂商：<a href="corp_detail.php?corp=<?php echo $bug_detail['wybug_corp'].'">'.$bug_detail['wybug_corp'];?></a></h3>
		<h3 class='wybug_author'>漏洞作者：	<a href="whitehat_detail.php?whitehat=<?php echo $bug_detail['wybug_author'].'">'.$bug_detail['wybug_author'];?></a></h3>
		<h3 class='wybug_date'>提交时间：<?php echo $bug_detail['wybug_date'];?></h3>
		<h3>修复时间：<?php echo $bug_detail['wybug_open_date'];?></h3>
		<h3 class='wybug_open_date'>公开时间：<?php echo $bug_detail['wybug_open_date'];?></h3>
		<h3 class='wybug_type'>漏洞类型：<?php echo $bug_detail['wybug_type'];?></h3>
		<h3 class='wybug_level'>危害等级：<?php echo $bug_detail['wybug_level'];?></h3>
		<h3>自评Rank：<?php echo $bug_detail['wybug_rank_0'];?></h3>
		<h3 class='wybug_status'>漏洞状态：<?php echo $bug_detail['wybug_status'];?></h3>

		<h3>漏洞来源：		<a href="http://www.wooyun.org">http://www.wooyun.org</a>，如有疑问或需要帮助请联系 <a class="__cf_email__" href="/cdn-cgi/l/email-protection" data-cfemail="28404d4458685f4747515d4606475a4f">[email&#160;protected]</a><script data-cfhash='f9e31' type="text/javascript">/* <![CDATA[ */!function(t,e,r,n,c,a,p){try{t=document.currentScript||function(){for(t=document.getElementsByTagName('script'),e=t.length;e--;)if(t[e].getAttribute('data-cfhash'))return t[e]}();if(t&&(c=t.previousSibling)){p=t.parentNode;if(a=c.getAttribute('data-cfemail')){for(e='',r='0x'+a.substr(0,2)|0,n=2;a.length-n;n+=2)e+='%'+('0'+('0x'+a.substr(n,2)^r).toString(16)).slice(-2);p.replaceChild(document.createTextNode(decodeURIComponent(e)),c)}p.removeChild(t)}}catch(u){}}()/* ]]> */</script></h3>
		<h3>Tags标签：
												<span class="tag"><a><?php echo "无";?></a></span>
								
</h3>
		<h3>
		<!-- Baidu Button BEGIN -->
        <div id="share">
        	<div style="float:right; margin-right:100px;font-size:12px">
            <span class="fav-num"><a id="collection_num">4</a>人收藏</span>
			<a style="text-decoration:none; font-size:12px" href="javascript:void(0)" class="fav-add btn-fav">收藏</a>
<script type="text/javascript">
var token="";
var id="<?php echo $bug_detail['wybug_id'];?>";

$(".btn-fav").click(function(){ CollectBug(id,token); });

</script>
			</div>
            <span style="float:left;">分享漏洞：</span>
            <div id="bdshare" class="bdshare_b" style="line-height: 12px;"><img src="picture/type-button-5_1.jpg" />
                <a class="shareCount"></a>
            </div>
        </div>
        <!-- Baidu Button END -->
    	</h3>
		<hr align="center"/<?php echo str_replace('static.loner.fm',$_SERVER['SERVER_ADDR'],$bug_detail['wybug_detail']);?>
		<hr align="center"/<?php echo $bug_detail['wybug_reply'];?>
					
		
<hr align="center" />
<script type="text/javascript">
var bugid="<?php echo $bug_detail['wybug_id'];?>";
var bugRating="-3";
var myRating="";
var ratingCount="0";



function ShowBugRating(k){
	var ratingItems=$(".myrating span");
	$.each(ratingItems,function(i,n){
		var nk=parseInt($(n).attr("rel"));
		if(nk<=k){
			$(n).addClass("on");
		}else{
			$(n).removeClass("on");
		}
	});
	$(".myrating span").hover(
		function(){
			$("#ratingShow").html($(this).attr("data-title"));
		},
		function(){
			$("#ratingShow").html("");
		}
	);
}
$(document).ready(function(){
	if(myRating==""){
		var ratingItems=$(".myrating span");
		$(".myrating span").hover(
			function(){
				$(this).addClass("hover");
				var k=parseInt($(this).attr("rel"));
				$.each(ratingItems,function(i,n){
					var nk=parseInt($(n).attr("rel"));
					if(nk<k) $(n).addClass("on");
					if(nk>k) $(n).removeClass("on");
				});
				$("#ratingShow").html($(this).attr("data-title"));
			},
			function(){
				$(this).removeClass("hover");
				if($("#myRating").val()==""){
					$.each(ratingItems,function(i,n){
						$(n).removeClass("on");
					});
				}
				$("#ratingShow").html("");
			}
		);

		$(".myrating span").click(function(){
			var rating=$(this).attr("rel");
			var k=parseInt($(this).attr("rel"));
			$.post("/ajaxdo.php?module=bugrating",{"id":bugid,"rating":rating,"token":$("#token").val()},function(re){
				//消除操作绑定
				$(".myrating span").unbind();
				re=parseInt(re);
				switch(re){
					case 1:
						$("#ratingShow").html(_LANGJS.RATING_SUCCESS);
						$("#ratingSpan").html(parseInt($("#ratingSpan").html())+1);
						$.each(ratingItems,function(i,n){
							var nk=parseInt($(n).attr("rel"));
							if(nk<=k){
								$(n).addClass("on");
							}else{
								$(n).removeClass("on");
							}
						});
						ShowBugRating(rating);
						break;
					case 2:
						$("#ratingShow").html(_LANGJS.LOGIN_FIRST);
						break;
					case 4:
						$("#ratingShow").html(_LANGJS.RATING_BUGS_DONE);
						break;
					case 6:
						$("#ratingShow").html(_LANGJS.RATING_BUGS_SELF);
						break;
					default:break;
				}
			});
		});
	}else{
		if(ratingCount>2){
			ShowBugRating(bugRating);
		}else{
			ShowBugRating(-3);
		}
	}
});

</script>


	</div>
	<div id="footer">
        <span class="copyright fleft">
    		Copyright &copy; 2010 - 2016 <a href="#">loner.fm</a>, All Rights Reserved
    		<a href="http://www.miibeian.gov.cn/">京ICP备1s04a3x28号-1</a>
    	</span>
        <span class="other fright">
                        <a>行业观点</a>
            · <a>法律顾问</a>
            · <a href="contact.php">联系我们</a>
            · <a href="help.php">帮助</a>
            · <a href="about.php">关于</a>
			
			
        </span>
    </div>
<?php mysql_close($conn); ?>





</body>

</html>
