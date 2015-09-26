# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html

import scrapy


class WooyunItem(scrapy.Item):
    # define the fields for your item here like:
    # name = scrapy.Field()
    title = scrapy.Field()
    author = scrapy.Field()
    datetime = scrapy.Field()
    category = scrapy.Field()
    url = scrapy.Field()
    html = scrapy.Field()
    
    image_urls = scrapy.Field() 
    images = scrapy.Field()     

