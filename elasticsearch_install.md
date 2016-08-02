Elasticsearch Install
=============================

当进行全文搜索时，使用mongodb效率很低，且比较耗内存；一种解决办法是使用elasticsearch引擎，通过mongo-connector将数据同步到elasticsearch后进行快速搜索。

elasticsearch默认对中文是按照每个单独的汉字来进行分词的，所以查询中文非常的蛋疼。现在搜索中文的分词都基本采用IK插件，经过反复安装完成测试，还未达到理想的效果。可能是有地方没搞对，还请各位大牛们指点指点。

安装elasticsearch(通过apt-get)
--------
1、安装repo库

```bash
wget -qO - https://packages.elastic.co/GPG-KEY-elasticsearch | sudo apt-key add -
echo "deb https://packages.elastic.co/elasticsearch/2.x/debian stable main" | sudo tee -a /etc/apt/sources.list.d/elasticsearch-2.x.list
```
2、安装JDK和elasticsearch

```bash
sudo apt-get update 
sudo apt-get install openjdk-7-jdk elasticsearch
```
3、将elasticseach加入到系统启动项中

```bash
sudo update-rc.d elasticsearch defaults 95 10
sudo /etc/init.d/elasticsearch start
```
4、测试一下，安装完成运行后elasticsearch会在9200端口上进行监听

```bash
curl -X GET http://localhost:9200
{
  "name" : "Sebastian Shaw",
  "cluster_name" : "elasticsearch",
  "version" : {
    "number" : "2.3.4",
    "build_hash" : "e455fd0c13dceca8dbbdbb1665d068ae55dabe3f",
    "build_timestamp" : "2016-06-30T11:24:31Z",
    "build_snapshot" : false,
    "lucene_version" : "5.5.0"
  },
  "tagline" : "You Know, for Search"
}
```


配置mongodb
-------

1、编辑/etc/mongodb.conf，增加：
	
	replSet=rs0 #这里是指定replSet的名字 
	oplogSize=100 #这里是指定oplog表数据大小（太大了不支持）

重启动mongodb

```bash
sudo service mongodb restart
```
2，进入mongodb shell，初始化replicSet

```bash
mongo
rs.initiate( {"_id" : "rs0", "version" : 1, "members" : [ { "_id" : 0, "host" : "127.0.0.1:27017" } ]}) 
```
3，搭建好replicSet之后，退出mongo shell重新登录，提示符会变成：rs0:PRIMARY>，就可以退出Mongodb


安装mongo-connector，将数据同步到elasticsearch
-------

```bash
sudo pip install mongo-connector elastic2_doc_manager
sudo mongo-connector -m localhost:27017 -t localhost:9200 -d elastic2_doc_manager
```
显示Logging to mongo-connector.log.后将会把mongodb数据库的信息同步到elasticsearch中，完全同步完成估计需要10-15分钟时间，同步期间不能中断，否则可能导致elasticsearch与mongodb数据不一致。


安装中文分词插件elasticsearch-analysis-ik
-------

1、从github下载编译好好的插件

```bash
cd ~  
sudo apt-get install unzip wget
wget https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v1.9.4/elasticsearch-analysis-ik-1.9.4.zip
unzip elasticsearch-analysis-ik-1.9.4.zip
```

2、将插件复制到elasticsearch的plugins目录

```bash
sudo cp -R  ~/elasticsearch-analysis-ik/ /usr/share/elasticsearch/plugins
sudo chmod +rx /usr/share/elasticsearch/plugins/elasticsearch-analysis-ik
```

3、修改elasticsearch.yml配置，定义插件配置

```bash
sudo vi /etc/elasticsearch/elasticsearch.yml
```
在最后增加:

	index:
	  analysis:
	    analyzer:
	      ik_syno:
	          type: custom
	          tokenizer: ik_max_word
	          filter: [my_synonym_filter]
	      ik_syno_smart:
	          type: custom
	          tokenizer: ik_smart
	          filter: [my_synonym_filter]
	    filter:
	      my_synonym_filter:
	          type: synonym
	          synonyms_path: analysis/synonym.txt
	          
同时，增加一个空的analysis/synonym.txt文件：

```bash
sudo mkdir /etc/elasticsearch/analysis
sudo touch /etc/elasticsearch/analysis/synonym.txt
```

4、重启elasticsearch

```bash
sudo service elasticsearch restart
```
启用全文搜索
-------
1、安装elasticsearch-py

```bash
pip install elasticsearch
```
2、更新app.py

```bash
cd ~/wooyun_public
git pull
```

3、修改app.py

```bash
vi ~/wooyun_public/flask/app.py
修改:
	SEARCH_BY_ES = True
```
参考链接
-------
1、[https://imququ.com/post/elasticsearch.html](https://imququ.com/post/elasticsearch.html)

2、[https://github.com/medcl/elasticsearch-analysis-ik](https://github.com/medcl/elasticsearch-analysis-ik)

3、[http://es.xiaoleilu.com](http://es.xiaoleilu.com)

4、[http://www.cnblogs.com/ciaos/p/3601209.html](http://www.cnblogs.com/ciaos/p/3601209.html)