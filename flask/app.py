#!/usr/bin/env python
#-*- coding: utf-8 -*-
import math
import re
import pymongo
from flask import Flask, request, session, g, redirect, url_for, abort, render_template, flash
#setting:
MONGODB_SERVER = 'localhost'
MONGODB_PORT = 27017
MONGODB_DB = 'wooyun'
MONGODB_COLLECTION = 'wooyun_list'
ROWS_PER_PAGE = 20
#flask app:
app = Flask(__name__)
app.config.from_object(__name__)
#monogodb connection string
connection_string = "mongodb://%s:%d" % (app.config['MONGODB_SERVER'],app.config['MONGODB_PORT'])

def get_search_regex(keywords,search_by_html):
    keywords_regex = {}
    kws = [ks for ks in keywords.strip().split(' ') if ks!='']
    field_name = 'html' if search_by_html else 'title'
    if len(kws)>0:
        reg_pattern = re.compile('|'.join(kws),re.IGNORECASE)
        #keywords_regex[field_name]={'$regex':'|'.join(kws)}
        keywords_regex[field_name]=reg_pattern
    
    return keywords_regex
     
def search_mongodb(keywords,page,search_by_html):        
    client = pymongo.MongoClient(connection_string)
    db = client[app.config['MONGODB_DB']]
    keywords_regex = get_search_regex(keywords,search_by_html)
    collection = db[app.config['MONGODB_COLLECTION']]
    #get the total count and page:
    total_rows = collection.find(keywords_regex).count()
    total_page = int(math.ceil(total_rows / (app.config['ROWS_PER_PAGE']*1.0)))
    page_info={'current':page,'total':total_page,'total_rows':total_rows,'rows':[]}
    #get the page rows
    if total_page >0 and page <= total_page:
        row_start = (page-1)*app.config['ROWS_PER_PAGE']
        cursors = collection.find(keywords_regex,{'wooyun_id':1,'title':1,'datetime':1,'author':1,'bug_type':1})\
            .sort('datetime',pymongo.DESCENDING).skip(row_start).limit(app.config['ROWS_PER_PAGE'])
        for c in cursors:
            c['datetime']=c['datetime'].strftime('%Y-%m-%d')
            page_info['rows'].append(c)
    client.close()
    #
    return page_info

def get_wooyun_total_count():
    client = pymongo.MongoClient(connection_string)
    db = client[app.config['MONGODB_DB']]
    collection = db[app.config['MONGODB_COLLECTION']]
    total_count = collection.find().count()
    client.close()

    return total_count


@app.route('/')
def index():
    total_count = get_wooyun_total_count()
    return render_template('index.html',total_count=total_count,title=u'乌云公开漏洞搜索')

@app.route('/search', methods=['get'])
def search():
    keywords = request.args.get('keywords')
    page = int(request.args.get('page',1))
    search_by_html = True if 'true' == request.args.get('search_by_html','false').lower() else False
    if page<1: page = 1
    #
    page_info = search_mongodb(keywords,page,search_by_html)
    #
    return render_template('search.html',keywords=keywords,page_info=page_info,search_by_html=search_by_html,title=u'搜索结果-乌云公开漏洞搜索')

def main():
    app.run(debug=True)

if __name__ == '__main__':
	main()
