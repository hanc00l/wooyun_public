<?php 


require('conn.php');


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="x-ua-compatible" content="ie=7"/>
<title> 厂商列表 | 乌云网 | WooYun.org </title>
<meta name="author" content="80sec"/>
<meta name="copyright" content="http://www.wooyun.org/"/>
<meta name="keywords" content="乌云官方漏洞,路人甲,未授权访问/权限绕过,wooyun,应用安全,web安全,系统安全,网络安全,漏洞公布,漏洞报告,安全资讯。"/>
<meta name="description" content="|WooYun是一个位于厂商和安全研究者之间的漏洞报告平台,注重尊重,进步,与意义"/>
<link rel="icon" href="/favicon.ico" sizes="32x32"/>
<link href="css/style_1.css" rel="stylesheet" type="text/css"/>
<link href="css/index.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery-1.4.2.min_1.js" type="text/javascript"></script>
</head>
<body id="bugDetail">
<style>#myBugListTab{position:relative;display:inline;border:none}#myBugList{position:absolute;display:none;margin-left:309px;* margin-left:-60px; * margin-top:18px ; border:#c0c0c0 1px solid; padding:2px 7px; background:#FFF }#myBugList li{text-align:left}</style>

<div class="banner">
<div class="logo">
<h1>WooYun.org</h1>
<div class="weibo"><iframe width="136" height="24" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0" scrolling="no" border="0" src="http://widget.weibo.com/relationship/followbutton.php?language=zh_cn&width=136&height=24&uid=1981622273&style=2&btn=red&dpc=1"></iframe>
</div>

		</div>
		
				<div class="login">
								<?php 				   if(isset($_SESSION['username'])){
					 if($chk_count['is_fromwooyun'] == 1){
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
            <input type="text" name="q" id="search_input" />
            <input type="submit" value="搜索" id="search_button" />
        </form>
	</div>

	<div class="bread" style="padding-top: 4px;">
		<div style="float:left">当前位置：<a href="index.php">WooYun</a> >> <a>厂商信息</a></div>
			</div>
<div class="content">
<div style="padding:0 0 675px 0;">

<input type="hidden" id="token" style="display:none" value="" />
		<h3 style="font-size:18px;font:font:Microsoft YaHei,Helvetica,Arial,Sans-Serif;padding:0 0 5px 0">厂商列表(30)</h3>
		<div  style="width:98%;text-align:center;font-size:15px;padding:5px 0 5px 10px;"><p style="align:center;line-height:35px;background-color:#ABABAB;">WooYun关注所有有力量影响互联网，改变人们生活的企业各种层面上的安全问题，你可以在WooYun注册为厂商来关注和修复自己企业的安全问题</p>
		</div>
<div class="wybug_date" style="padding:0 30px 0 30px;">

<?php   @$result_data_desc = mysql_query("select wybug_date,wybug_corp,count(DISTINCT wybug_id) as count from bugs group by wybug_corp order by SUM(wybug_rank_fromcorp) desc limit 30");
  while(@$row = mysql_fetch_array(@$result_data_desc)){
	 echo '<ul style="font-size:12px;list-style:none;line-height:25px;">';
	 echo '<li  style="width:20%;height:25px;background-color:#FFFFFF;float:left" >'.@$row['wybug_date'].'</li>';
	 echo '<li  style="width:60%;height:25px;background-color:#FFFFFF;float:left" ><a href="corp_detail.php?corp='.@$row['wybug_corp'].'">'.@$row['wybug_corp'].'</a></li>';
	 echo '<li  style="width:20%;height:25px;background-color:#FFFFFF;float:left" >'.@$row['count'].'</li>';
	 echo '</ul>';
 }
 mysql_close(@$conn);
?>

</div>


</div>
<!--content-end-->
</div>
	<div id="footer">
        <span class="copyright fleft">
    		Copyright &copy; 2010 - 2016 <a href="#">wooyun.org</a>, All Rights Reserved
    		<a href="http://www.miibeian.gov.cn/">京ICP备15041338号-1</a>
    	</span>
        <span class="other fright">
                        <a href="impression">行业观点</a>
            · <a href="lawer">法律顾问</a>
            · <a href="contactus">联系我们</a>
            · <a href="help">帮助</a>
            · <a href="about">关于</a>
        </span>
    </div>
</body>
</html>