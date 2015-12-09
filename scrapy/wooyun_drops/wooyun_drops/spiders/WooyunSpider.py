# -*- coding: utf-8 -*-
from datetime import datetime
from urllib import unquote
import pymongo
import scrapy
from wooyun_drops.items import WooyunItem
from scrapy.conf import settings


class WooyunSpider(scrapy.Spider):
    name = "wooyun"
    allowed_domains = ["wooyun.org"]
    start_urls = [
        'http://drops.wooyun.org/'
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
        # <span class="pages">第 1 页，共 80 页</span>
        total_pages = response.xpath("//div[@class='wp-pagenavi']/span[@class = 'pages']/text()").re(u"共 (\d+) 页")[0]
        if self.page_max == 0:
            end_page = int(total_pages)
        else:
            end_page = self.page_max

        for page in range(1,end_page + 1):
            page_url = "http://drops.wooyun.org/page/%d"%page
            yield scrapy.Request(page_url, self.parse_post_urls)

    def parse_post_urls(self, response):
        post_urls = response.xpath("//div[@class = 'post']/h2[@class = 'entry-title']/a/@href").extract()
        for url in post_urls:
            url = response.urljoin(url)
            if self.update or not self.__search_mongodb(url):
                yield scrapy.Request(url, self.parse_detail)

    def parse_detail(self,response):
        item = WooyunItem()
        item['url'] = unquote(response.url)
        item['category'] = unquote(response.url).split('//')[1].split('/')[1]
        item['title'] = response.xpath("//title/text()").extract()[0].split(u"|")[0].strip()
        item['author'] = response.xpath("//div[@class = 'entry-meta']/a/@href").extract()[0].split("/")[2]
        dt = response.xpath("//div[@class = 'entry-meta']/time/text()").extract()[0].split(' ')[0].split('/')
        dt_time = response.xpath("//div[@class = 'entry-meta']/time/text()").extract()[0].split(' ')[1].split(':')
        item['datetime'] = datetime(int(dt[0]),int(dt[1]),int(dt[2]),int(dt_time[0]),int(dt_time[1]))
        item['image_urls'] = []
        if self.local_store:
            image_urls = response.xpath("//p/img/@src").extract()
            #skip the https image download
            #skip www.quip.com,can'n be downloaded
            for u in image_urls:
                if 'https://' not in u and 'www.quip.com' not in u:
                    item['image_urls'].append(u)

        item['html'] = response.body.decode('utf-8','ignore')

        return item

    def __search_mongodb(self,url):
        #
        wooyun_drops_exsist = True if self.collection.find({'url':url}).count()>0 else False
        #
        return wooyun_drops_exsist
