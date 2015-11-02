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
        wooyun_id_exsist = True if self.collection.find({'wooyun_id':item['wooyun_id']}).count()>0 else False
        if wooyun_id_exsist == False:
            self.collection.insert_one(dict(post_data))
            self.log.debug('wooyun_id:%s added to mongdb!'%item['wooyun_id'],)
        else:
            if spider.update:
                self.collection.update_one({'wooyun_id':item['wooyun_id']},{'$set':dict(post_data)})
                self.log.debug('wooyun_id:%s exist,update!' %item['wooyun_id'])
            else:
                self.log.debug('wooyun_id:%s exist,not update!' %item['wooyun_id'])

        return item

class WooyunSaveToLocalPipeline(object):
    def process_item(self,item,spider):
        #
        if spider.local_store == False:
            return item
        #
        if item['wooyun_id'] == None or item['wooyun_id'] =='':
            self.log.debug('There is none wooyun_id,this item do not be saved!')
            return item
        #
        post_data = copy.deepcopy(item)
        if self.__process_html(post_data) == False:
            return item
        #
        path_name = settings['LOCAL_STORE'] + item['wooyun_id'] + '.html'
        #save file as utf-8 format
        with codecs.open(path_name,mode='w',encoding='utf-8',errors='ignore') as f:
            f.write(post_data['html'])

        return item

    def __process_html(self,item):
        if item['html'] == None or item['html'] == '':
            self.log.debug('the wooyunid:%s html body is empty!'%item['wooyun_id'])
            return False
        #deal the img
        for img in item['images']:
            #处理部份图片存放于http://www.wooyun.org时，使用/upload/..形式的路径
            if img['url'].startswith('http://www.wooyun.org'):
                img['url'] = img['url'].replace('http://www.wooyun.org','')
            item['html'] = re.sub('<img src=[\'\"]%s[\'\"]'%img['url'],'<img src=\'%s\''%img['path'],item['html'])
        #deal css
        item['html'] = re.sub(r'<link href=\"/css/style\.css','<link href=\"css/style.css',item['html'])
        #deal script
        item['html'] = re.sub(r'<script src=\"https://static\.wooyun\.org/static/js/jquery\-1\.4\.2\.min\.js','<script src=\"js/jquery-1.4.2.min.js',item['html'])

        return True
