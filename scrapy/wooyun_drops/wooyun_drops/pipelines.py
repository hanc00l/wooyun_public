# -*- coding: utf-8 -*-
import logging
import re
from datetime import datetime
import copy
import codecs
import pymongo
from scrapy.conf import settings
from scrapy.exceptions import DropItem

# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/en/latest/topics/item-pipeline.html

class MongoDBPipeline(object):
    def __init__(self):
        self.connection_string = "mongodb://%s:%d" % (settings['MONGODB_SERVER'],settings['MONGODB_PORT'])
   
    def open_spider(self, spider):
        self.client = pymongo.MongoClient(self.connection_string)
        self.db = self.client[settings['MONGODB_DB']]
        self.collection = self.db[settings['MONGODB_COLLECTION']]
        self.log = logging.getLogger(spider.name)

    def close_spider(self, spider):
        self.client.close()

    def process_item(self, item, spider):
        #
        post_data = copy.deepcopy(item)
        post_data.pop('image_urls')
        post_data.pop('images')
        #
        post_data['category'] = self.__map_category(post_data['category'])
        #
        wooyun_drops_exsist = True if self.collection.find({'url':item['url']}).count()>0 else False
        if not wooyun_drops_exsist :
            self.collection.insert_one(dict(post_data))
            self.log.debug('wooyun_drop url:%s added to mongdb!'%item['url'],)
        else:
            if spider.update:
                self.collection.update_one({'url':item['url']},{'$set':dict(post_data)})
                self.log.debug('wooyun_drop url:%s exist,update!' %item['url'])
            else:
                self.log.debug('wooyun_drop url:%s exist,not update!' %item['url'])

        return item

    def __map_category(self,category_name):
        category_map={'papers':u'漏洞分析','tips':u'技术分享','tools':u'工具收集','news':u'业界资讯',\
                        'web':u'web安全','pentesting':u'渗透案例','mobile':u'移动安全','wireless':u'无线安全',\
                        'database':u'数据库安全','binary':u'二进制安全'}
        if category_name in category_map:
            return category_map[category_name]

        return category_name

class WooyunSaveToLocalPipeline(object):
    def process_item(self,item,spider):
        #
        if not spider.local_store:
            return item
        #
        if item['url'] == None or item['url'] =='':
            self.log.debug('There is none wooyun_drop url,this item do not be saved!')
            return item
        #
        post_data = copy.deepcopy(item)
        if not self.__process_html(post_data):
            return item
        #
        path_name = settings['LOCAL_STORE'] + self.__process_local_filename(item['url'])
        #save file as utf-8 format
        with codecs.open(path_name,mode='w',encoding='utf-8',errors='ignore') as f:
            f.write(post_data['html'])
        
        return item

    def __process_local_filename(self,url):
        urlsep = url.split('//')[1].split('/')
        return '%s-%s.html'%(urlsep[1],urlsep[2])

    def __process_html(self,item):
        if item['html'] == None or item['html'] == '':
            self.log.debug('the wooyunid:%s html body is empty!'%item['wooyun_id'])
            return False
        jquery_js = "http://wooyun.b0.upaiyun.com/static/js/jquery.min.js"
        bootstrap_js = "http://wooyun.b0.upaiyun.com/static/js/bootstrap.min.js"
        main_css = "http://wooyun.b0.upaiyun.com/static/css/95e46879.main.css"
        bootstrap_css = "http://wooyun.b0.upaiyun.com/static/css/bootstrap.min.css"

        wooyun_jquery_js = "static/drops/js/jquery.js"
        wooyun_bootstrap_js = "static/dropsjs/bootstrap.min.js"
        wooyun_main_css = "static/drops/css/95e46879.main.css"
        wooyun_bootstrap_css = "static/drops/css/bootstrap.min.css"

        item['html'] = item['html'].replace(jquery_js, wooyun_jquery_js).replace(bootstrap_js, wooyun_bootstrap_js)
        item['html'] = item['html'].replace(main_css, wooyun_main_css).replace(bootstrap_css, wooyun_bootstrap_css)
        
        for it in item['images']:
            item['html'] = item['html'].replace(it['url'], 'static/drops/%s'%it['path'])

        return True


