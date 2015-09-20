# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html

import scrapy


class WooyunItem(scrapy.Item):
    # define the fields for your item here like:
    # name = scrapy.Field()
    datetime = scrapy.Field()
    datetime_open = scrapy.Field()
    title = scrapy.Field()
    wooyun_id = scrapy.Field()
    author = scrapy.Field()
    bug_type = scrapy.Field()
    html = scrapy.Field()
    #
    image_urls = scrapy.Field()
    images = scrapy.Field()
