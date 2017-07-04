<?php 


require('conn.php');
@$wybug_author0=isset($_GET['whitehat'])?$_GET['whitehat']:'路人甲';
@$wybug_author = str_replace("'","",$wybug_author0);
@$author = mysql_query("select * from bugs where wybug_author='".$wybug_author."'");
@$bugs_author=mysql_fetch_array($author);
@$whitehat1 = mysql_query("SELECT * from whitehats where whitehat ='".$wybug_author."'");
@$whitehats = mysql_fetch_array($whitehat1);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="x-ua-compatible" content="ie=7"/>
<title> <?php echo @$bugs_author['wybug_author'];?> | 乌云网 | WooYun.org </title>
<meta name="author" content="80sec"/>
<meta name="copyright" content="http://www.wooyun.org/"/>
<meta name="keywords" content="乌云官方漏洞,路人甲,未授权访问/权限绕过,wooyun,应用安全,web安全,系统安全,网络安全,漏洞公布,漏洞报告,安全资讯。"/>
<meta name="description" content="|WooYun是一个位于厂商和安全研究者之间的漏洞报告平台,注重尊重,进步,与意义"/>
<link rel="icon" href="/favicon.ico" sizes="32x32"/>
<link href="css/style_1.css" rel="stylesheet" type="text/css"/>
<link href="css/index.css" rel="stylesheet" type="text/css"/>

<link href="css/whitehat_detail.css" rel="stylesheet" type="text/css"/>
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
		<div style="float:left">当前位置：<a href="index.php">首页</a> >> <a href="whitehat_detail.php?whitehat=<?php echo @$whitehats['whitehat'];?>">白帽子信息</a></div>
			</div>
<div class="content">
<!--头像标签开始-->
<div style="background-color:#F0F0F0;height:150px">
    <div>
     <img src="images/whitehat.jpg" style="float:left;padding:10px 0 10px 10px;width:120px;">
<!--头像标签结束-->
    </div>
	 <div style="float:left;padding:10px 0 0 20px;">
     
	 <span style="display:inline;"><a href="whitehat_detail.php?whitehat=<?php echo $whitehats['whitehat'];?>" style="font-size:20px;color:#000000"><strong><?php echo $whitehats['whitehat'];?></strong></a> <p style="display:inline;font-size:16px;color:#8E8E8E">( 路人 | Rank: <?php echo $whitehats['Ranks'];?> )</p></span>
	 <p style="padding:8px 0 0 0;">他于 <?php echo @$whitehats['join_date'];?> 注册，已来到乌云 164 天</p>
	 <p style="padding:8px 0 0 0;">个人主页: <a>http://</a></p>

    </div>
</div>
<div style="background-color:#F0F0F0;height:90px;">
	 <div style="background-color:#E0E0E0;float:left;padding:10px 0 10px 20px;width:980px">
     
	
	 <p style="padding:0 0 3px 5px;">擅长领域</p>
	 <p style="padding:5px 5px 5px 5px;background-color:#FFFFFF; width:140px"> 他暂时没有添加擅长领域 </p>

    </div>
</div>

<div style="padding:0 0 200px 0;background-color:#d0d0d0;">
 <ul class="tabs">
<li>
<input type="radio" name="tabs" id="tab1" checked />
<label for="tab1">漏洞列表</label>
<div id="tab-content1" class="tab-content">
<p><ul style="font-size:14px;list-style:none;line-height:20px;">
<li style="width:14.3%;height:20px;background-color:#C4C4C4;float:left" >提交日期</li>
<li style="width:55%;height:20px;background-color:#C4C4C4;float:left;margin-left:0.2%;" >漏洞名称</li> 
<li style="width:20%;height:20px;background-color:#C4C4C4;float:left;margin-left:0.2%;" >漏洞类型</li> 
<li style="width:10%;height:20px;background-color:#C4C4C4;float:left;margin-left:0.3%" >漏洞等级</li> 
</ul>
<?php    $num = "15"; //每页显示30条
		@$page=isset($_GET['page'])?intval($_GET['page']):1;				
		@$total=mysql_num_rows($author); //查询数据的总数total
        $pagenum=ceil($total/$num);
		if(@$page>@$pagenum || $page == 0){
           exit;
        } 
    @$offset=($page-1)*$num;
    @$result_bugs22 = mysql_query("select * from bugs where wybug_author='".$wybug_author."' order by wybug_date desc limit $offset,15");
  while(@$row22 = mysql_fetch_array($result_bugs22)){
	 echo '<ul style="font-size:12px;list-style:none;line-height:25px;">';
	 echo '<li  style="width:15%;height:25px;background-color:#FFFFFF;float:left" >'.$row22['wybug_date'].'</li>';
	 echo '<li  style="width:55%;height:25px;background-color:#FFFFFF;float:left" ><a href="bug_detail.php?wybug_id='.$row22['wybug_id'].'">'.$row22['wybug_title'].'</a></li>';
	  echo '<li  style="width:20%;height:25px;background-color:#FFFFFF;float:left" ><a>'.$row22['wybug_type'].'</a></li>';
	 echo '<li  style="width:10%;height:25px;background-color:#FFFFFF;float:left" >'.$row22['wybug_level'].'</li>';
	 echo '</ul>';
 }

?></p>
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
echo '<center> 共 '.$total.' 条记录';
echo '，'.$pagenum.' 页 ';
echo '<a href="whitehat_detail.php?whitehat='.@$bugs_author['wybug_author'].'&page=1">首页</a>|';
echo '<a href="whitehat_detail.php?whitehat='.@$bugs_author['wybug_author'].'&page='.$prepage.'">上一页</a>|';
echo '<a href="whitehat_detail.php?whitehat='.@$bugs_author['wybug_author'].'&page='.$nextpage.'">下一页</a>|';
echo '<a href="whitehat_detail.php?whitehat='.@$bugs_author['wybug_author'].'&page='.$pagenum.'">末页</a></center>';
?>
</div>


</div>
</li>

<li>
<input type="radio" name="tabs" id="tab2" />
<label for="tab2">文章列表</label>
<div id="tab-content2" class="tab-content">
<p><ul style="font-size:14px;list-style:none;line-height:20px;">
<li style="width:14.3%;height:20px;background-color:#C4C4C4;float:left" >提交日期</li>
<li style="width:55%;height:20px;background-color:#C4C4C4;float:left;margin-left:0.2%;" >标题</li> 
<li style="width:20%;height:20px;background-color:#C4C4C4;float:left;margin-left:0.2%;" >领域</li> 
<li style="width:10%;height:20px;background-color:#C4C4C4;float:left;margin-left:0.3%" >回复</li> 
</ul>
<?php    
    @$result_articles = mysql_query("select * from article where author_name='".$wybug_author."' order by article_id");
  while(@$row23 = mysql_fetch_array($result_articles)){
	 echo '<ul style="font-size:12px;list-style:none;line-height:25px;">';
	 echo '<li  style="width:15%;height:25px;background-color:#FFFFFF;float:left" >'.$row23['date'].'</li>';
	 echo '<li  style="width:55%;height:25px;background-color:#FFFFFF;float:left" ><a href="../article.php?id='.$row23['article_id'].'">'.$row23['title'].'</a></li>';
	  echo '<li  style="width:20%;height:25px;background-color:#FFFFFF;float:left" ><a href="../area.php?id='.$row23['area_id'].'">'.$row23['area_name'].'</a></li>';
	 echo '<li  style="width:10%;height:25px;background-color:#FFFFFF;float:left" >'.$row23['comm_count'].'</li>';
	 echo '</ul>';
 }

?></p>

</div>
</li>
</ul>
			</div>

</div>
	<div id="footer" style="padding-top:320px;background-color:#E0E4E7">
        <span class="copyright fleft">
    		Copyright &copy; 2010 - 2016 <a href="#">wooyun.org</a>, All Rights Reserved
    		<a href="http://www.miibeian.gov.cn/">京ICP备15041338号-1</a>
    	</span>
        <span class="other fright" style="padding:0 0 0 0">
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