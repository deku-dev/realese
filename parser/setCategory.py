from typing import Text, final
from unicodedata import name
import common
import bs4, requests
import mysqlconn


connect = mysqlconn.getConnection()
try:
  distCat = common.getCategory("https://s8.torents-igruha.org/")
  with connect.cursor() as cursor:
    id = 1
    for url, names in distCat.items():
      cursor.execute("INSERT INTO `category`(`cat_id`, `cat_name`, `cat_link`) VALUES (%s,%s,%s)", (id, names, url))
      print(url, names)
      id += 1
    connect.commit()

finally:
  connect.close()