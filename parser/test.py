# coding=utf-8
import asyncio
import ctypes
import datetime
import glob
import io
import json
import logging
import logging.config
import os
import pickle
import re
import string
import time
from pprint import pprint
from threading import Semaphore, Thread, Lock, BoundedSemaphore
from urllib.parse import urlparse

import bs4
import parsel
import pymysql
import requests
from colorama import Back, Fore, Style, init
from nltk import Text, word_tokenize
from nltk.corpus import stopwords
from nltk.probability import FreqDist
from rutermextract import TermExtractor
from summa.summarizer import summarize
from var_dump import var_dump

import common
import mysqlconn
import timing
from gameparse import GameParse as GP
from listgame import ListGame as LG
from senddata import SendData as SD
init(autoreset=True) 
logging.config.fileConfig('logging.ini',disable_existing_loggers=False)
logger = logging.getLogger(__name__)





# for pagelink in getCategory("https://s5.torents-igruha.org/"):
#   listPage = LG(pagelink)
#   listPage.getlistlink()
#   for linkGame in listPage.listGame:
#     gameParse = GP(linkGame)
#     gameParse.getAllData()
#     print(gameParse.name)

text = "Как насчет того, чтобы взять ситуацию под свой контроль и постараться помочь маленькому еноту запастись едой. Если раньше енотам приходилось довольствоваться тем, что есть на мусорках, то теперь ситуация кардинально изменилась. Енот, за которого вам предстоит играть имеет навыки крафта, строительства, умеет скрытно перемещаться и даже сражаться при помощи девайсов. И используя все эти возможности можно будет постепенно добиваться успеха и запасаться пищей, только поначалу предстоит скачать торрент Wanted Raccoon на ПК и только после этого можно будет смело начинать действовать.<br /><br /><H2>Бешенный и хитрый енот</H2><br />Как вы уже поняли, играть за енота будет довольно весело, ведь он не знает грани и готов активно действовать, чтобы добиваться результата. Поначалу вам нужно будет построить свою личную базу, куда вы будете прятать свое награбленное. Как только хранилище будет готово, можно начинать действовать и стараться активно использовать свои возможности. Главное внимательно исследовать окружение, бороться с людьми и пытаться любыми средствами для воровства еды. И чем больше будет награбленного, тем больше шансов пережить зиму. Естественно, люди будут пытаться вас прогнать и даже побить, поэтому стоит быть готовы к постоянному противостоянию.<br /><br />Помогите животному добиться хорошего результата и запастись качественной едой. Различные примочки, девайсы и навыки енота, абсолютно все поможет вам искать пищу, складировать ее и перемещать в свою базу. Но первым делом вам следует скачать Wanted Raccoon через торрент на русском языке, только после этого можно будет отправляться в путешествие. Желаем вам удачи и хорошей наживы!<br /><br /><H2>Особенности игры</H2><br /><ul><li>Возможность поиграть за веселого енота.</li><br /><li>Большой перечень различных девайсов, которые помогут добиться успеха.</li><br /><li>Личная база для хранения награбленной пищи.</li><br /><li>Интерактивное окружение и возможность использовать разные предметы.</li><br /><li>Сражайтесь с людьми и заманивайте их в ловушки.</li></ul>Вы никогда не мечтали стать истинным богом? Тогда вам наверняка понравится идея и возможность скачать Super Worldbox через торрент. Это приключение обещает быть интересным, так как вам предстоит проявить свои лучшие навыки управленца и почувствовать все величие божественной силы. Не стоит волноваться, управлять цивилизациями не нужно, вы просто будете наблюдать со стороны и принимать лишь некоторые решения. Кстати, мир за которым вы будете следить состоит из фэнтэзи рас: эльфы, орки, люди и т.д. Ваша задача заключается в том, чтобы вы поддерживали баланс развития и эволюции, так как дисбаланс может стать причиной страшной войны.Изначально вы будете наслаждаться маленькими пикселями и просто умилительно радоваться маленьким достижениям. Затем начнется корректировка мира, которая будет заключаться в управлении погодными явлениями и прочими интересными стихийными возможностями. Это вам пригодится для корректировки баланса в мире. Можно создать новый материк ли устроить засуху, выпустить огромного монстра или наоборот, сотворить жилу с сокровищами. Главное стараться все это балансировать и не принимать скоротечных решений. Все должно внимательно обдумываться и хотя бы немного просчитываться.Не стоит забывать об опасностях извне, которыми могут стать разные бедствия и прочие неприятные моменты. С ними вам тоже предстоит бороться или хотя бы создавать условия для того, чтоб цивилизации могли пережить негодование. В любом случае будет интересно чувствовать себя богом и постепенно добиваться хорошего результата. Возможно, небольшая пиксельная графика поначалу будет отпугивать вас, но как только вы вольетесь в это приключение, оторваться уже будет сложно. Учитывайте, что некоторые божественные способности абсолютно рандомны и неподконтрольны. Это добавит интереса и непредсказуемых ситуаций во время игры. Желаем вам удачи!"

otherText = "Days Gone является игрой для PC в жанре приключенческий и шутер. События разворачиваются после двух лет всемирной пандемии, вследствие чего люди превратились в зомби, так называемыми фрикерами. Игрок возьмет на себя роль бывшего бандита по имени Дикон Сейнт-Джон и будет блуждать в поисках приключений по всей земле.Зомби-апокалипсис – самая распространенная теория о гибели человечества. Уже было создано десятки разных произведений, которые рассказывают о разных исходах появления зомби. И сегодня у вас будет отличная возможность скачать торрент Days Gone (Жизнь После) pc версию и вновь поближе познакомиться с новой теорией, которая рассказывает о том, как люди приноровились к выживанию в самых жестоких условиях, полному окружению зомби и исследованию мира. Но правда ли зомби стали главной проблемой? Или на фоне всеобщего хаоса люди начали демонстрировать свою истинную натуру? Все это предстоит выяснить вам, поэтому стоит быть предельно внимательным и осторожным.Главные преимущества игрыВы повстречаете на своем пути немало выживших и будете взаимодействовать с некоторыми фрикерами, осматривать заброшенные города и опустевшие покинутые здания, в поисках необходимых запасов. У вас будет прекрасная возможность объехать всю землю, в которой не осталось прежней жизни, из-за смертельного вируса. У главного героя байкера и охотника за головами будет крутой мотоцикл, на котором он будет колесить по опасной дороге, кишащей ордой жутких людей и кровожадных зомби.Путешествие и сраженияВам предстоит взять на себя роль обычного байкера, который отправился противостоять зомби и искать убежище, куда он отправил свою жену и ребенка во имя спасения. Он уже не видел их долгое время, но не теряет надежды найти их вновь. Путешествует по миру он на своем байке, сражается с зомби и мародёрам, ищет полезные ресурсы и банально выживает. Кстати, зомби в этой игре представлены более чем жестокими, у них есть свои улья, они чуют врага издалека и хорошо выдерживают выстрелы с огнестрельного оружия. Поэтому, когда вы решите скачать Days Gone через торрент на ПК на русском, то стоит запастись терпением и внимательностью, выживать в таких условиях будет крайне сложно.Зомби стали самой настоящей проблемой, так как превратили людей не только в опасных существ, но еще и раскрыли их истинную сущность. Поэтому придется быть крайне внимательным и стараться не поддаваться на провокации, ведь люди порой могут быть опаснее любого монстра.Особенности игры Жизнь ПослеПутешествуйте на мощном байке, который не даст вам заскучать.Используйте большой арсенал вооружения.Исследуйте мир и отбирайте у врагов ценные ресурсы.Сражайтесь с толпами зомби при помощи разного оружия и ловушек."

def removeSpecChar(text, chars):
  return "".join([ch for ch in text if ch not in chars])

def removeTags(text):
  text = text.lower()
  replTag = r"<\W{0,1}[a-zA-Z0-9]{1,10}\W{0,3}>"
  return re.sub(replTag, " ", text, 0, re.MULTILINE)

def analyzeText(text):
  text = text.lower()
  text = removeTags(text)
  specChars = string.punctuation + '\n"«»\t'
  text = removeSpecChar(text, specChars)
  text = removeSpecChar(text, string.digits)
  return text
# with open('testdesc.txt',"w",encoding="utf8") as file:
#   file.write(text)


# analyzeText(text)

def rutExtract(text, textTag):
  text = removeTags(text)
  termExtr = TermExtractor()
  termDict = { term.normalized: 1 / term.count for term in termExtr(text) }
  for termer in termExtr(textTag, weight=lambda term: termDict.get(term.normalized, 1.0) * term.count):
    print(termer, termer.count)

# rutExtract(text, otherText)

numThread = 124
def addInOneFile(numThread):
  with open("description.txt", "w", encoding="utf8") as file:
    for thread in range(0, numThread):
      with open("description"+str(thread)+".txt", "r", encoding='utf8') as f:
        print(thread)
        textFile = f.read()
      file.write(textFile)
      os.remove("description"+str(thread)+".txt")

# addInOneFile(numThread)

# lg = GP("https://s5.torents-igruha.org/5447-raid-shadow-legends.html")

# print()
# text = removeTags(text)

# termExp = TermExtractor()
# res = termExp.tokenizer(text)
# print(res)
# for term in termExp(text):

def test():
  return "hello", "hi", "goodbye"

def test2(one, two, three):
  print(two, one, three)

# init(autoreset=True) 
# date = "2030-05"
# # dates = time.strftime("%Y-%m-%d", time.strptime(date, "%Y-%m"))

# reqt = "SELECT `id` FROM `torrent_link` WHERE `link`=%s"
# connect = mysqlconn.getConnection()
# try:
#   with connect.cursor() as cursor:
#     cursor.execute(reqt, ("https://s5.torednts-igruha.org/1405-the-godfather-ii.html"))
#     result = cursor.fetchone()
#     connect.commit()
#     if result:
#       print(result)

# finally:
#   connect.close()
# lock = Lock()
from queue import Queue

class ControlParser(Thread):
  def __init__(self, pageCat, numThread):
    Thread.__init__(self)
    self.catlink = pageCat
    self.numTh = numThread

  def run(self):
    semaphor = BoundedSemaphore(5)

    """Запуск потока"""
    semaphor.acquire()

    time.sleep(1)
    print(self.getName())
    logger.debug("Test thread name")
    semaphor.release()



def createThread():
  threads = []

  numThread = 0
  for catLink in range(0, 400):
    
    contrThread = ControlParser(catLink, numThread)
    contrThread.setName("Thread-"+str(catLink))
    contrThread.setDaemon(True)
    threads.append(contrThread)
    contrThread.start()
    numThread += 1


  for t in threads:
    t.join()
  logger.debug(Back.RED+Fore.BLACK+"End all thread in main")


def main():
  # allCateg = common.getCategory('https://s5.torents-igruha.org/') 
  createThread()

main()
