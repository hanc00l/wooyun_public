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
MONGODB_COLLECTION_BUGS = 'wooyun_list'
MONGODB_COLLECTION_DROPS = 'wooyun_drops'
ROWS_PER_PAGE = 20
#flask app:
app = Flask(__name__)
app.config.from_object(__name__)
#monogodb connection string
connection_string = "mongodb://%s:%d" % (app.config['MONGODB_SERVER'],app.config['MONGODB_PORT'])
content ={'by_bugs':\
                    {'mongodb_collection':app.config['MONGODB_COLLECTION_BUGS'],'template_html':'search_bugs.html'},\
          'by_drops':\
                    {'mongodb_collection':app.config['MONGODB_COLLECTION_DROPS'],'template_html':'search_drops.html'},\
}
def get_search_regex(keywords,search_by_html):
    keywords_regex = {}
    kws = [ks for ks in keywords.strip().split(' ') if ks!='']
    field_name = 'html' if search_by_html else 'title'
    if len(kws)>0:
        reg_pattern = re.compile('|'.join(kws),re.IGNORECASE)
        #keywords_regex[field_name]={'$regex':'|'.join(kws)}
        keywords_regex[field_name]=reg_pattern
    
    return keywords_regex
     
def search_mongodb(keywords,page,content_search_by,search_by_html):        
    client = pymongo.MongoClient(connection_string)
    db = client[app.config['MONGODB_DB']]
    keywords_regex = get_search_regex(keywords,search_by_html)
    collection = db[content[content_search_by]['mongodb_collection']]
    #get the total count and page:
    total_rows = collection.find(keywords_regex).count()
    total_page = int(math.ceil(total_rows / (app.config['ROWS_PER_PAGE']*1.0)))
    page_info={'current':page,'total':total_page,'total_rows':total_rows,'rows':[]}
    #get the page rows
    if total_page >0 and page <= total_page:
        row_start = (page-1)*app.config['ROWS_PER_PAGE']
        cursors = collection.find(keywords_regex)\
            .sort('datetime',pymongo.DESCENDING).skip(row_start).limit(app.config['ROWS_PER_PAGE'])
        for c in cursors:
            c['datetime']=c['datetime'].strftime('%Y-%m-%d')
            if 'url' in c:
                urlsep = c['url'].split('//')[1].split('/')
                c['url_local'] = '%s-%s.html'%(urlsep[1],urlsep[2])
            page_info['rows'].append(c)
    client.close()
    #
    return page_info

def get_wooyun_total_count():
    client = pymongo.MongoClient(connection_string)
    db = client[app.config['MONGODB_DB']]
    collection_bugs = db[app.config['MONGODB_COLLECTION_BUGS']]
    total_count_bugs = collection_bugs.find().count()
    collection_drops = db[app.config['MONGODB_COLLECTION_DROPS']]
    total_count_drops = collection_drops.find().count()
    client.close()

    return (total_count_bugs,total_count_drops)


@app.route('/')
def index():
    total_count_bugs,total_count_drops = get_wooyun_total_count()
    return render_template('index.html',total_count_bugs=total_count_bugs,total_count_drops=total_count_drops,title=u'乌云公开漏洞、知识库搜索')

@app.route('/search', methods=['get'])
def search():
    keywords = request.args.get('keywords')
    page = int(request.args.get('page',1))
    search_by_html = True if 'true' == request.args.get('search_by_html','false').lower() else False
    content_search_by = request.args.get('content_search_by','by_bugs')
    if page<1: page = 1
    #
    page_info = search_mongodb(keywords,page,content_search_by,search_by_html)
    #
    return render_template(content[content_search_by]['template_html'],keywords=keywords,page_info=page_info,search_by_html=search_by_html,title=u'搜索结果-乌云公开漏洞、知识库搜索')

def main():
    app.run(debug=True)

if __name__ == '__main__':
	main()
