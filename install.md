wooyun_public在Ubuntu下的安装
=============================

以下为在ubuntu14.04和16.04的安装过程，需要安装的依赖组件：

+ python 2.7和pip
+ mongodb
+ scrapy 
+ flask 或 tornado
+ pymongo 

步骤
--------
1、安装python、pip、mongodb

```bash
sudo apt-get install python python-pip mongodb
```
2、安装scrapy

```bash
安装scrapy如果报错，则先apt-get安装下述依赖包，然后安装pip安装lxml后即可正常安装scrapy
sudo apt-get install libxml2-dev libxslt1-dev python-dev zlib1g-dev libevent-dev python-openssl

sudo pip install lxml
sudo pip install scrapy
```
3、安装pymongo和flask（或tornado）

```bash
sudo pip install flask pymongo
(sudo pip install tornado)
```
4、从github下载源码

```bash
git clone https://github.com/hanc00l/wooyun_public
```


