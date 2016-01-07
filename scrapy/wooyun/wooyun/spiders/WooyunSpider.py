# -*- coding: utf-8 -*-
from datetime import datetime
import pymongo
import scrapy
from wooyun.items import WooyunItem
from scrapy.conf import settings


class WooyunSpider(scrapy.Spider):
    name = "wooyun"
    allowed_domains = ["wooyun.org"]
    start_urls = [
        'http://wooyun.org/bugs/new_public/'
    ]

    def __init__(self,page_max=settings['PAGE_MAX_DEFAULT'],local_store=settings['LOCAL_STORE_DEFAULT'],\
            update=settings['UPDATE_DEFAULT'],*args, **kwargs):
        self.page_max = int(page_max)
        self.local_store = 'true' == local_store.lower()
        self.update = 'true' == update.lower()

        self.connection_string = "mongodb://%s:%d" % (settings['MONGODB_SERVER'],settings['MONGODB_PORT'])
        self.client = pymongo.MongoClient(self.connection_string)
        self.db = self.client[settings['MONGODB_DB']]
        self.collection = self.db[settings['MONGODB_COLLECTION']]

    def closed(self,reason):
        self.client.close()

    def parse(self, response):
        total_pages = response.xpath("//p[@class='page']/text()").re('\d+')[1]
        if self.page_max == 0:
            end_page = int(total_pages)
        else:
            end_page = self.page_max
        for n in range(1,end_page + 1):
            page = "/bugs/new_public/page/%d" %n
            url = response.urljoin(page)
            yield scrapy.Request(url, self.parse_list)

    def parse_list(self,response):
        links = response.xpath('//tbody/tr/td/a/@href').extract()
        for url in links:
            wooyun_id = url.split('/')[2]
            if self.update == True or self.__search_mongodb(wooyun_id) == False:
                url = response.urljoin(url)
                yield scrapy.Request(url, self.parse_detail)

    def parse_detail(self,response):
        item = WooyunItem()
        item['wooyun_id'] = response.xpath('//*[@id="bugDetail"]/div[5]/h3[1]/a/@href').extract()[0].split('/')[2]
        item['title'] = response.xpath('//title/text()').extract()[0].split("|")[0]
        item['bug_type'] = response.xpath("//h3[@class='wybug_type']/text()").extract()[0].split(u'：')[1].strip()
        #item['bug_type'] = response.xpath('//*[@id="bugDetail"]/div[5]/h3[7]/text()').extract()[0].split(u'：')[1].strip()
        #some author not text,for examp:
        #http://wooyun.org/bugs/wooyun-2010-01010
        #there will be error while parse author,so do this
        try:
            #item['author'] = response.xpath("//h3[@class='wybug_author']/a/text()").extract()[0]
            item['author'] = response.xpath('//*[@id="bugDetail"]/div[5]/h3[4]/a/text()').extract()[0]
        except:
            item['author'] ='<Parse Error>'
        #the response.body type is str,so we need to convert to utf-8
        #if not utf-8,saving to mongodb may have some troubles
        item['html'] = response.body.decode('utf-8','ignore')
        #dt = response.xpath("//h3[@class='wybug_date']/text()").re("[\d+]{4}-[\d+]{2}-[\d+]{2}")[0].split('-')
        dt = response.xpath('//*[@id="bugDetail"]/div[5]/h3[5]/text()').re("[\d+]{4}-[\d+]{2}-[\d+]{2}")[0].split('-')
        item['datetime'] = datetime(int(dt[0]),int(dt[1]),int(dt[2]))
        #dt = response.xpath("//h3[@class='wybug_open_date']/text()").re("[\d+]{4}-[\d+]{2}-[\d+]{2}")[0].split('-')
        dt = response.xpath('//*[@id="bugDetail"]/div[5]/h3[6]/text()').re("[\d+]{4}-[\d+]{2}-[\d+]{2}")[0].split('-')
        item['datetime_open'] = datetime(int(dt[0]),int(dt[1]),int(dt[2]))
        #images url for download
        item['image_urls']=[]
        if self.local_store:
            #乌云图片目前发两种格式，一种是http://static.wooyun.org/wooyun/upload/,另一格式是/upload/...
            #因此，对后一种在爬取时，增加http://www.wooyun.org，以形成完整的url地址
            #同时，在piplines.py存放时，作相应的反向处理
            image_urls = response.xpath("//img[contains(@src, '/upload/')]/@src").extract()
            for u in image_urls:
                if self.__check_ingnored_image(u):
                    continue
                if u.startswith('/'):
                    u = 'http://www.wooyun.org' + u
                item['image_urls'].append(u)
        return item

    def __check_ingnored_image(self,image_url):
        for ignored_url in settings['IMAGE_DOWLOAD_IGNORED']:
            if ignored_url in image_url:
                return True

        return False
        
    def __search_mongodb(self,wooyun_id):
        #
        wooyun_id_exsist = True if self.collection.find({'wooyun_id':wooyun_id}).count()>0 else False
        #
        return wooyun_id_exsist
