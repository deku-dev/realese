import bs4, requests


def getCategory(page):
  catPage = bs4.BeautifulSoup(requests.get(page).text, "html5lib")
  catList = catPage.select("#menuigruha li a")
  return [item["href"] for item in catList]