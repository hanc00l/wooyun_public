# -*- coding: utf-8 -*-
import logging
import re
from datetime import datetime
import copy
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
        wooyun_record = self.collection.find_one({'wooyun_id':item['wooyun_id']})
        if wooyun_record == None:
            post_data = copy.deepcopy(item)
            post_data.pop('image_urls')
            post_data.pop('images')
            self.collection.insert_one(dict(post_data))
            self.log.debug('wooyun_id:%s added to mongdb!'%item['wooyun_id'],)
        else:
            self.log.debug('wooyun_id:%s exist!' %item['wooyun_id'])

        return item

class WooyunSaveToLocalPipeline(object):
    def process_item(self,item,spider):
        #
        if spider.local_store == False:
            return item
        #
        if item['wooyun_id'] == None or item['wooyun_id'] =='':
            raise DropItem('There is none wooyun_id,this item do not be saved!')
        #
        self.__process_html(item)
        #
        path_name = settings['LOCAL_STORE'] + item['wooyun_id'] + '.html'
        with open(path_name,'w') as f:
            f.write(item['html'])
        
        return item

    def __process_html(self,item):
        if item['html'] == None or item['html'] == '':
            raise DropItem('the wooyunid:%s html body is empty!'%item['wooyun_id'])
        #deal the img
        for img in item['images']:
            item['html'] = re.sub('<img src=[\'\"]%s[\'\"]'%img['url'],'<img src=\'%s\''%img['path'],item['html'])
        #deal css
        item['html'] = re.sub(r'<link href=\"/css/style\.css','<link href=\"css/style.css',item['html'])
        #deal script
        item['html'] = re.sub(r'<script src=\"https://static\.wooyun\.org/static/js/jquery\-1\.4\.2\.min\.js','<script src=\"js/jquery-1.4.2.min.js',item['html'])
        

