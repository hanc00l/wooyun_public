Elasticsearch Install
=============================

当进行全文搜索时，使用mongodb效率很低，且比较耗内存；解决办法是使用elasticsearch引擎，通过mongo-connector将数据同步到elasticsearch后进行快速搜索。

安装elasticsearch
--------

1、安装JDK（或者JRE）

```bash
sudo apt-get install openjdk-7-jdk
```
2、下载elasticseach

```bash
wget https://download.elastic.co/elasticsearch/release/org/elasticsearch/distribution/tar/elasticsearch/2.3.4/elasticsearch-2.3.4.tar.gz
tar xvf elasticsearch-2.3.4.tar.gz
```

3、运行elasticsearch

```bash
cd elasticsearch-2.3.4/bin
./elasticsearch
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


安装中文分词插件elasticsearch-analysis-ik
-------

1、从github下载编译好好的插件

```bash
cd ~  
sudo apt-get install unzip
wget https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v1.9.4/elasticsearch-analysis-ik-1.9.4.zip
unzip elasticsearch-analysis-ik-1.9.4.zip
```

2、将插件复制到elasticsearch的plugins目录

```bash
cp -r elasticsearch-analysis-ik elasticsearch-2.3.4/plugins
```

3、修改elasticsearch.yml配置，定义插件配置

```bash
vi elasticsearch-2.3.4/config/elasticsearch.yml
```
在最后增加:

	index.analysis.analyzer.ik.type : 'ik'
	index.analysis.analyzer.default.type : 'ik'

4、退出并重启elasticsearch

```bash
 elasticsearch-2.3.4/bin/elasticsearch -d
 (-d表示以后台方式运行）
```

安装mongo-connector，将数据同步到elasticsearch
-------

```bash
sudo pip install mongo-connector elastic2_doc_manager
sudo mongo-connector -m localhost:27017 -t localhost:9200 -d elastic2_doc_manager
```
显示Logging to mongo-connector.log.后将会把mongodb数据库的信息同步到elasticsearch中，完全同步完成估计需要30分钟左右，同步期间不能中断，否则可能导致elasticsearch与mongodb数据不一致。

在同步过程中，可能会报错：

```bash
OperationFailed: ConnectionTimeout caused by - ReadTimeoutError(HTTPConnectionPool(host=u'localhost', port=9200): Read timed out. (read timeout=10))
2016-08-04 17:24:53,372 [ERROR] mongo_connector.oplog_manager:633 - OplogThread: Failed during dump collection cannot recover! Collection(Database(MongoClient(u'127.0.0.1', 27017), u'local'), u'oplog.rs')
2016-08-04 17:24:54,371 [ERROR] mongo_connector.connector:304 - MongoConnector: OplogThread <OplogThread(Thread-7, started 140485117060864)> unexpectedly stopped! Shutting down
```

####解决办法:

修改timeout值，从默认的10改为200

```bash
sudo vi /usr/local/lib/python2.7/dist-packages/mongo_connector/doc_managers/elastic2_doc_manager.py
```
	将：
	self.elastic = Elasticsearch(hosts=[url],**kwargs.get('clientOptions', {}))
	
	修改为：
	self.elastic = Elasticsearch(hosts=[url],timeout=200, **kwargs.get('clientOptions', {}))


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
	SEARCH_BY_ES = 'auto'
```
参考链接
-------
1、[https://imququ.com/post/elasticsearch.html](https://imququ.com/post/elasticsearch.html)

2、[https://github.com/medcl/elasticsearch-analysis-ik](https://github.com/medcl/elasticsearch-analysis-ik)

3、[http://es.xiaoleilu.com](http://es.xiaoleilu.com)

4、[http://www.cnblogs.com/ciaos/p/3601209.html](http://www.cnblogs.com/ciaos/p/3601209.html)

5、[https://segmentfault.com/a/1190000002470467](https://segmentfault.com/a/1190000002470467)

6、[https://github.com/medcl/elasticsearch-analysis-ik/issues/207](https://github.com/medcl/elasticsearch-analysis-ik/issues/207)

7、[https://github.com/mongodb-labs/mongo-connector/wiki/Usage%20with%20ElasticSearch](https://github.com/mongodb-labs/mongo-connector/wiki/Usage%20with%20ElasticSearch)