<?php 

require('conn.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="x-ua-compatible" content="ie=7"/>
<title> 白帽子 | 乌云网 | WooYun.org </title>
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
					 if(@$chk_count['is_fromwooyun'] == 1){
						 echo '<p style="padding:5px 10px 0 0">欢迎白帽子 <a href="whitehat_detail.php?whitehat='.$_SESSION['username'].'">'.$_SESSION['username'].'</a> | <a href="logout.php">注销</a></p>';
					 }
					 else{
					 echo  '<p style="padding:5px 10px 0 0">欢迎 '.$_SESSION['username'].' | <a href="logout.php">注销</a></p>';
					 }
                  }else{
					  echo '<a href="../login.php">登录</a> | <a class="reg">注册</a>';
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
		<div style="float:left">当前位置：<a href="index.php">WooYun</a> >> <a>白帽子信息</a></div>
			</div>
<div class="content">
<div style="padding:0 0 695px 0;">

<input type="hidden" id="token" style="display:none" value="" />
		<h3 style="font-size:18px;font:font:Microsoft YaHei,Helvetica,Arial,Sans-Serif;padding:0 0 5px 0">白帽子</h3>
		<div  style="width:98%;text-align:left;font-size:15px;padding:5px 0 5px 10px;"><p style="line-height:35px;background-color:#BEBEBE;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WooYun将一切对安全极为感兴趣，对事物运行的原理有着天生的好奇心，愿意将技术回归技术，愿意为其他朋友做出贡献的人定义为白帽子，你可以通过在WooYun注册提交漏洞来成为白帽子中的一员</p>
		</div>
<div class="wybug_date" style="padding:0 30px 0 30px;">
<input type="hidden" id="token" style="display:none" value="" />
<div class="wybug_date" style="padding:0 30px 0 30px;">
<ul style="font-size:14px;list-style:none;line-height:20px;">
<li style="width:19.3%;height:20px;background-color:#E0E0E0;float:left" >注册日期</li>
<li style="width:50%;height:20px;background-color:#E0E0E0;float:left;margin-left:0.2%" >昵称</li> 
<li style="width:15%;height:20px;background-color:#E0E0E0;float:left;margin-left:0.2%" >提交漏洞数</li> 
<li style="width:15%;height:20px;background-color:#E0E0E0;float:left;margin-left:0.3%" >Rank值</li> 
</ul>
<?php    $num = "60"; //每页显示30条
		@$page=isset($_GET['page'])?intval($_GET['page']):1;				
		$total="9245"; //查询数据的总数total
        @$pagenum=ceil($total/$num);
		if($page>$pagenum || $page == 0){
           exit;
        } 
  @$offset=($page-1)*$num;
  @$result_join_desc = mysql_query("select * from whitehats order by join_date asc limit $offset,30");
  while(@$row = mysql_fetch_array($result_join_desc)){
	 echo '<ul style="font-size:12px;list-style:none;line-height:25px;">';
	 echo '<li  style="width:20%;height:25px;background-color:#FFFFFF;float:left" >'.$row['join_date'].'</li>';
	 echo '<li  style="width:50%;height:25px;background-color:#FFFFFF;float:left" ><a href="whitehat_detail.php?whitehat='.$row['whitehat'].'">'.$row['whitehat'].'</a></li>';
	 echo '<li  style="width:15%;height:25px;background-color:#FFFFFF;float:left" >'.$row['bug_count'].'</li>';
	 echo '<li  style="width:15%;height:25px;background-color:#FFFFFF;float:left" >'.$row['Ranks'].'</li>';
	 echo '</ul>';
 }

?>

</div>
<!--显示分页-start-->
<div  style="float:right;padding:10px 30px 0 0"><?php 
@$page = $_GET['page']?$_GET['page']:1;//当前页数，默认是1
if($page==1){
	$prepage=1;
}else{
	$prepage=$page-1;
}
if($page==$pagenum){
	$nextpage=$pagenum;
}else{
	$nextpage=$page+1;
}
echo '<center> 共 9245 条记录';
echo '，155 页 ';
echo '<a href="whitehats.php?page=1">首页</a>|';
echo '<a href="whitehats.php?page='.$prepage.'">上一页</a>|';
echo '<a href="whitehats.php?page='.$nextpage.'">下一页</a>|';
echo '<a href="whitehats.php?page='.$pagenum.'">末页</a></center>';


?>
</div>
<!--显示分页-end-->


</div>
<!--content-end-->
</div>
	<div style="padding:110px 0 0 0;">
        <span class="copyright fleft">
    		Copyright &copy; 2010 - 2016 <a href="#">wooyun.org</a>, All Rights Reserved
    		<a href="http://www.miibeian.gov.cn/">京ICP备15041338号-1</a>
    	</span>
        <span class="other fright" style="padding:20px 0 0 0">
                        <a>行业观点</a>
            · <a>法律顾问</a>
            · <a href="contact.php">联系我们</a>
            · <a href="help.php">帮助</a>
            · <a href="about.php">关于</a>
        </span>
    </div>
<?php  mysql_close($conn);?>
</body>
</html>