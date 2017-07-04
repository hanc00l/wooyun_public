<?php 
require('conn.php');
function get_sql($retfield,$where_field,$s){
    $sa=explode(" ",$s);
    $where_str = "";
    $where_str_link = " where ";
    foreach($sa as $a){
        if(trim($a)== "") continue;
        $where_str .= $where_str_link;
        $where_str .= $where_field;
        $where_str .= " like '%";
        $where_str .= $a;
        $where_str .= "%'";
        $where_str_link =" and ";
    }
    $sql = "select ".$retfield." from bugs ".$where_str." order by wybug_date desc";

    return $sql;
}

@$q0=isset($_GET['q'])?$_GET['q']:'SQL注射';
$q = addslashes($q0);
$table_fieldname="wybug_title";
if(isset($_GET["detail"])) $table_fieldname="wybug_detail";
$sql = get_sql("id",$table_fieldname,$q);
$qs = mysql_query($sql);
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
		<div style="float:left">当前位置：<a href="index.php">首页</a> >> <a href="whitehat_detail.php?whitehat=<?php echo @$whitehats['whitehat'];?>">检索结果</a></div>
			</div>
<div class="content">
<!--头像标签开始-->

	 <div style="float:left;padding:10px 0 14px 20px;background-color:#FCFCFC;width:100%">
     
<span style="display:inline;"><p style="display:inline;font-size:20px;color:#7B7B7B">搜索关键字：</p><p style="display:inline;font-size:20px;color:#CE0000"> <?php echo htmlspecialchars($q, ENT_COMPAT);?> </p> <p style="display:inline;font-size:20px;color:#7B7B7B">(共 <?php echo mysql_num_rows($qs);?> 条记录)</p></span>

    </div>
	
<!--检索结果-start-->
<div>

  <?php    $num = "15"; //每页显示30条
		@$page=isset($_GET['page'])?intval($_GET['page']):1;				
		@$total=mysql_num_rows($qs); //查询数据的总数total
        @$pagenum=ceil($total/$num);
		if($page>$pagenum || $page == 0){
           exit;
        } 
  @$offset=($page-1)*$num;
  $sql=get_sql("wybug_id,wybug_title,wybug_date,wybug_author",$table_fieldname,$q);
  $sql .= " limit ".$offset.",15";
  $bugs_result2222=mysql_query($sql);  
  while(@$row223 = mysql_fetch_array($bugs_result2222)){
	     echo '<div style="padding:0 0 30px 20px;width:100%"><span style="font-size:20px;line-height:25px;">';
         echo '<p style="padding:0 0 14px 0;"><a href="bug_detail.php?wybug_id='.$row223['wybug_id'].'">'.$row223['wybug_title'].'</a></p>';
	    
	 	 echo '<p style="display:inline;font-size:14px;line-height:25px;">提交日期：<a>'.$row223['wybug_date'].'</a></p>';
		 echo '<p style="display:inline;font-size:14px;line-height:25px;"> 作者：<a href="whitehat_detail.php?whitehat=';
         echo $row223['wybug_author'].'">'.$row223['wybug_author'].'</a>';
	     echo '</p></span> </div>';
 }

?>

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
echo '<a href="searchbug.php?q='.$q.'&page=1">首页</a>|';
echo '<a href="searchbug.php?q='.$q.'&page='.$prepage.'">上一页</a>|';
echo '<a href="searchbug.php?q='.$q.'&page='.$nextpage.'">下一页</a>|';
echo '<a href="searchbug.php?q='.$q.'&page='.$pagenum.'">末页</a></center>';
?>
</div>


</div>
<!---检索结果-end-->


</div>
	<div id="footer" style="padding-top:20px;background-color:#E0E4E7">
        <span class="copyright fleft">
    		Copyright &copy; 2010 - 2016 <a href="#">wooyun.org</a>, All Rights Reserved
    		<a href="http://www.miibeian.gov.cn/">京ICP备15041338号-1</a>
    	</span>
        <span class="other fright" style="">
                        <a>行业观点</a>
            · <a>法律顾问</a>
            · <a href="contact.php">联系我们</a>
            · <a href="help.php">帮助</a>
            · <a href="about.php">关于</a>
			<script src="https://s4.cnzz.com/z_stat.php?id=1261218610&web_id=1261218610" language="JavaScript"></script>
        </span>
    </div>
<?php  mysql_close($conn);?>
</body>
</html>
