# wooyun_public 
**乌云公开漏洞、知识库爬虫和搜索**

**crawl and search for wooyun.org public bug(vulnerability) and drops**

![index](index.png)
![search](search.png)

### 1.安装相关组件
+ python 2.7和pip
+ mongodb
+ scrapy (pip install scrapy)
+ flask (pip install Flask)
+ pymongo (pip install pymongo) 

### 2.爬虫
+ **在爬取内容时，请先启动mongodb数据库：mongod &**

+ 乌云公开漏洞和知识库的爬虫分别位于目录scrapy/wooyun和scrapy/wooyun_drops

+ 运行scrapy crawl wooyun -a page_max=1  -a local_store=false -a update=false，有三个参数用于控制爬取：

    -a page_max: 控制爬取的页数，默认为1，如果值为0，表示所有页面
    
    -a local_store: 控制是否将每个漏洞离线存放到本地，默认为false
    
    -a update：控制是否重复爬取，默认为false
    
+ 第一次爬取全部内容时，用scrapy crawl wooyun -a page_max=0 -a update=true
  
+ 平时只爬取最近的更新时，用scrapy crawl wooyun -a page_max=1，可以根据自己的爬取频率和网站更新情况调整page_max的值
 
+ 全部公开漏洞的列表和每个漏洞的文本内容存在mongodb中，大概约2G内容；如果整站爬全部文本和图片作为离线查询，大概需要10G空间、2小时（10M电信带宽）；爬取全部知识库，总共约500M空间。（截止2015年10月）

### 3.搜索 
+ 漏洞搜索使用了Flask作为web server，bootstrap作为前端

+ **在搜索时，请确定已启动了mongodb数据库：mongod &**

+ 启动web server ：在flask目录下运行python app.py，默认端口是5000

+ 搜索：在浏览器通过http://localhost:5000进行搜索漏洞，多个关键字可以用空格分开。

### 4.为mongodb数据库创建索引（可选，以下为命令行操作）
	mongo
	use wooyun
	db.wooyun_list.ensureIndex({"datetime":1})
	db.wooyun_drops.ensureIndex({"datetime":1})

### 5.虚拟机

+ 虚拟机1：在2016年6月底爬的wooyun全部漏洞库和知识库内容，总共30G，网盘地址为： [http://pan.baidu.com/s/1o7IEaAQ](http://pan.baidu.com/s/1o7IEaAQ) 提取密码：d4cq 

+ 虚拟机2：已打包了一个安装了所有组件和程序的虚拟机（不包含具体内容），网盘地址为：[http://pan.baidu.com/s/1sj67KDZ](http://pan.baidu.com/s/1sj67KDZ) 密码：bafi
	
+ 虚拟机使用方法：
		
		1、使用vmware或virtualbox导入虚拟机
		2、登录用户名hancool,密码qwe123
		3、进入wooyun_public目录，先用git更新一下到最新的代码git pull
		4、分别进入wooyun_public目录下的wooyun和wooyun_drops，运行爬虫爬取数据（爬取全部数据并且本地离线缓存）：scrapy crawl wooyun -a  page_max=0 -a local_store=true -a update=true
		5、进入wooyun_publich目录下的flask，运行./app.py，启动web服务
		6、打开浏览器，输入http://ip:5000，ip为虚拟机的网卡地址（如果是vmware，则使用ifconfig eth0查看，如果是virtualbox为192.168.56.130）


### 6.其它

+ 本程序只用于技术研究和个人使用，程序组件均为开源程序，漏洞和知识库来源于乌云公开漏洞，版权归wooyun.org

+ hancool@163.com 2015.9
