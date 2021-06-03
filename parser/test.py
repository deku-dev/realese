import json
import re
from urllib.parse import urlparse

import bs4
import parsel
import pymysql
import requests

import mysqlconn
resp = requests.get("https://s4.torrents-igruha.org/repack-ot-mechanics/page/6/")

gameHtml = bs4.BeautifulSoup(resp.text, "html5lib")

connect = mysqlconn.getConnection()
request = "SELECT * FROM `game` WHERE `game_id`=%s"
try:
  with connect.cursor() as cursor:
    cursor.execute(request, (8))
    result = cursor.fetchone()
    connect.commit()
finally:
  connect.close()

print(result['name'])
