import json, requests, bs4, re, mysqlconn, pymysql 
from listgame import ListGame as LG
from gameparse import GameParse as GP
from senddata import SendData as SD

listGame = LG("https://s5.torents-igruha.org/")

# TODO: View doc for __main__.py, __init__.py and func main