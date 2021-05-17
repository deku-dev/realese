import json, requests, bs4, re, mysqlconn, pymysql

# class SendData:

class ParseData:
  def __init__(self):
    print("compete")



  def searchDot(self, string):
    listProp = ['Год выпуска', 'Жанр', 'Разработчик', 'Издательство', 'Платформа', 'Язык интерфейса', 'Язык озвучки', 'Таблетка', 'ОС', 'Процессор', 'Оперативная память', 'Видеокарта']
    worss = []
    for listr in string:
      if listr.find(':') == -1:
        for words in listProp:
          if listr.find(words) != -1:
            worss.append(listr.replace(words, words+':'))
      else:
        worss.append(listr)
    return worss

  def listParse(self, soup):
    item = str(soup)
    item = item.strip()
    return list(filter(None, item.split('<br/>')))

  def dictparse(self, listv):
    return dict(s.split(':',1) for s in self.searchDot(listv))

  
  