# coding=utf8
import requests, bs4, re, string, os, mysqlconn, pymysql
import logging
from listgame import ListGame as LG
from gameparse import GameParse as GP
from senddata import SendData as SD
from var_dump import var_dump
from threading import Thread, Lock
from nltk import word_tokenize
from nltk import Text
from nltk.probability import FreqDist
from nltk.corpus import stopwords
from rutermextract import TermExtractor
import logging.config
import threading
import pickle
import timing
import heapq
import common, traceback


from colorama import init, Fore, Back, Style
logging.config.fileConfig('logging.ini',disable_existing_loggers=False)
logger = logging.getLogger(__name__)
init(autoreset=True) 

numGameTeach = 0
numGameInsert = 0
listTeachGame = []

stopWords = ["а","е","и","ж","м","о","на","не","ни","об","но","он","мне","мои","мож","она","они","оно","мной","много","многочисленное","многочисленная","многочисленные","многочисленный","мною","мой","мог","могут","можно","может","можхо","мор","моя","моё","мочь","над","нее","оба","нам","нем","нами","ними","мимо","немного","одной","одного","менее","однажды","однако","меня","нему","меньше","ней","наверху","него","ниже","мало","надо","один","одиннадцать","одиннадцатый","назад","наиболее","недавно","миллионов","недалеко","между","низко","меля","нельзя","нибудь","непрерывно","наконец","никогда","никуда","нас","наш","нет","нею","неё","них","мира","наша","наше","наши","ничего","начала","нередко","несколько","обычно","опять","около","мы","ну","нх","от","отовсюду","особенно","нужно","очень","отсюда","в","во","вон","вниз","внизу","вокруг","вот","восемнадцать","восемнадцатый","восемь","восьмой","вверх","вам","вами","важное","важная","важные","важный","вдали","везде","ведь","вас","ваш","ваша","ваше","ваши","впрочем","весь","вдруг","вы","все","второй","всем","всеми","времени","время","всему","всего","всегда","всех","всею","всю","вся","всё","всюду","г","год","говорил","говорит","года","году","где","да","ее","за","из","ли","же","им","до","по","ими","под","иногда","довольно","именно","долго","позже","более","должно","пожалуйста","значит","иметь","больше","пока","ему","имя","пор","пора","потом","потому","после","почему","почти","посреди","ей","два","две","двенадцать","двенадцатый","двадцать","двадцатый","двух","его","дел","или","без","день","занят","занята","занято","заняты","действительно","давно","девятнадцать","девятнадцатый","девять","девятый","даже","алло","жизнь","далеко","близко","здесь","дальше","для","лет","зато","даром","первый","перед","затем","зачем","лишь","десять","десятый","ею","её","их","бы","еще","при","был","про","процентов","против","просто","бывает","бывь","если","люди","была","были","было","будем","будет","будете","будешь","прекрасно","буду","будь","будто","будут","ещё","пятнадцать","пятнадцатый","друго","другое","другой","другие","другая","других","есть","пять","быть","лучше","пятый","к","ком","конечно","кому","кого","когда","которой","которого","которая","которые","который","которых","кем","каждое","каждая","каждые","каждый","кажется","как","какой","какая","кто","кроме","куда","кругом","с","т","у","я","та","те","уж","со","то","том","снова","тому","совсем","того","тогда","тоже","собой","тобой","собою","тобою","сначала","только","уметь","тот","тою","хорошо","хотеть","хочешь","хоть","хотя","свое","свои","твой","своей","своего","своих","свою","твоя","твоё","раз","уже","сам","там","тем","чем","сама","сами","теми","само","рано","самом","самому","самой","самого","семнадцать","семнадцатый","самим","самими","самих","саму","семь","чему","раньше","сейчас","чего","сегодня","себе","тебе","сеаой","человек","разве","теперь","себя","тебя","седьмой","спасибо","слишком","так","такое","такой","такие","также","такая","сих","тех","чаще","четвертый","через","часто","шестой","шестнадцать","шестнадцатый","шесть","четыре","четырнадцать","четырнадцатый","сколько","сказал","сказала","сказать","ту","ты","три","эта","эти","что","это","чтоб","этом","этому","этой","этого","чтобы","этот","стал","туда","этим","этими","рядом","тринадцать","тринадцатый","этих","третий","тут","эту","суть","чуть","тысяч"]

def saveObj(obj, name ):
  with open('obj/'+ name + '.pkl', 'wb') as f:
    pickle.dump(obj, f, pickle.HIGHEST_PROTOCOL)

def loadObj(name):
  with open('obj/' + name + '.pkl', 'rb') as f:
    return pickle.load(f)



def removeTags(text):
  text = text.lower()
  replTag = r"<\W{0,1}[a-zA-Z0-9]{1,10}\W{0,3}>"
  return re.sub(replTag, " ", text, 0, re.MULTILINE)

def removeSpecChar(text, chars):
  return "".join([ch for ch in text if ch not in chars])

def analyzeText(text):
  specChars = string.punctuation + '\n"«»\t'
  text = text.lower()
  text = removeTags(text)
  text = removeSpecChar(text, specChars)
  text = removeSpecChar(text, string.digits)
  return text

def addInOneFile(thread):
  with open("file/description.txt", "w", encoding="utf8") as file:
    for thread in range(1, thread):
      with open("file/description"+str(thread)+".txt", "r", encoding='utf8') as f:
        # print(thread)
        textFile = f.read()
      file.write(textFile)
      os.remove("file/description"+str(thread)+".txt")
    logging.info("Add in ONE file end")
  
def termAllText():
  with open("file/description.txt", "w+", encoding="utf8") as f:
    text = f.read()
    termExtr = TermExtractor()
    termDict = { term.normalized: 1 / term.count for term in termExtr(text) }
    saveObj(termDict, "terms")

class ControlParser(Thread):
  def __init__(self, pageLink, numThread, mode=False):
    Thread.__init__(self)
    self.numTh = numThread
    self.mode = mode
    self.catLink = pageLink
    self.listPage = LG(self.catLink)
    

  def run(self):
    """Запуск потока"""
    self.connect = mysqlconn.getConnection()
    try:
      with self.connect.cursor() as self.cursor:
        logging.info("Thread analyzer #"+str(self.numTh)+" started")
        if(self.mode):
          logging.info("Mode tagging text start")
          self.taggingText()
        else:
          logging.info("Mode teachText text start")
          self.teachText()
    finally:
      self.connect.close()
    
  
  def teachText(self):
    global numGameTeach, listTeachGame
    lock = Lock()
    self.listPage.getAllGame()
    with open("file/description"+str(self.numTh)+".txt", "w", encoding='utf8') as f:
      for linkGame in self.listPage.listAllGame:
        gameParse = GP(linkGame)
        desc = gameParse.getDescription(True)
        name = gameParse.getName()
        if(linkGame in listTeachGame):
          continue
        logging.info("Teach game name -%s" % (name))
        f.write(analyzeText(desc))
        with lock:
          listTeachGame.append(linkGame)
          print("Teach game %6d %-60s"%(numGameTeach, name))
          numGameTeach += 1
    logging.info("Ended work thread teach #"+str(self.numTh))

  def taggingText(self):
    global numGameInsert
    self.listPage.getAllGame()
    lock = Lock()
    for linkGame in self.listPage.listAllGame:
      gameParse = GP(linkGame)
      desc = gameParse.getDescription(True)
      name = gameParse.getName()
      tagsComp = self.termOneText(desc)
      sendTags = SD(self.connect, self.cursor, linkGame)
      if(sendTags.checkGame()):
        continue
      sendTags.saveNewGame(*gameParse.getAllData())
      sendTags.setTags(tagsComp, name, ".".join(self.shortDesc(desc)))
      with lock:
        print("Save game %6d %-70s"%(numGameInsert, name))
        numGameInsert += 1
      # print("%-75s%-8d%3d%5d" % (gameParse.getName(), len(tagsComp), self.numTh, counterTa))
    logging.info("Ended work thread #"+str(self.numTh)+" Game addet")



  def termOneText(self,text):
    wordFreq = loadObj("terms")
    termExtr = TermExtractor()
    listWordTerm = [str(termer) for termer in termExtr(text, weight=lambda term: wordFreq.get(term.normalized, 1.0) * term.count)]
    # print(listWordTerm[:10])
    return ",".join(listWordTerm)

  def shortDesc(self, text):
    global stopWords
    sentences = re.split(r' *[\.\?!][\'"\)\]]* *', text)
    clean_text = text.lower()
    word_tokenize = clean_text.split()
    word2count = {}
    for word in word_tokenize:
      if word not in stopWords:
        if word not in word2count.keys():
          word2count[word] = 1
        else:
          word2count[word] += 1
    sent2score = {}
    for sentence in sentences:
      for word in sentence.split():
        if word in word2count.keys():
          if len(sentence.split(' ')) < 28 and len(sentence.split(' ')) > 9:
            if sentence not in sent2score.keys():
              sent2score[sentence] = word2count[word]
            else:
              sent2score[sentence] += word2count[word]
    # взвешенная гистограмма
    for key in word2count.keys():
      word2count[key] = word2count[key] / max(word2count.values())  
    return heapq.nlargest(3, sent2score, key=sent2score.get)

def createThread(pagelist, mode=False):
  semaphore = threading.BoundedSemaphore(50)
  threads = []
  numThread = 1
  for pageLink in pagelist:
    contrThread = ControlParser(pageLink, numThread, mode)
    contrThread.daemon = True
    print("Thread - %s started"%(contrThread.name))
    threads.append(contrThread)
    numThread += 1
  for t in threads:
    semaphore.acquire()
    
    t.start()
    semaphore.release()
  for t in threads:
    t.join()
  mode or termAllText()
  mode or addInOneFile(numThread)

def main():
  logging.info("Started program analyzer.py")
  # pageSite = ["https://s5.torents-igruha.org/newgames/page/"+str(page)+"/" for page in range(1,130)]
  # pageSite = ["https://s5.torents-igruha.org/newgames/page/3/"]
  pageSite = common.getCategory('https://s5.torents-igruha.org/')
  print("Start analyzer")
  print("Collect data games")
  createThread(pageSite)
  print("Start send to database game")
  createThread(pageSite, True)
