import requests
from bs4 import BeautifulSoup
import json
import sys

URL = "https://www.24ur.com"

# Funkcija, ki naredi request
def req(url):
    try:
        # Pošlji HTTP GET zahtevo na URL
        response = requests.get(url, timeout=10)
        # Preveri, če je bila zahteva uspešna (status code 200)
        response.raise_for_status() 
    except requests.exceptions.RequestException as e:
        # V primeru napake (ni povezave, timeout, ...) izpiši napako na stderr
        # in končaj izvajanje.
        print(json.dumps({"error": f"Napaka pri povezovanju: {e}"}), file=sys.stderr)
        quit()

    return BeautifulSoup(response.content, 'html.parser')

# Postrga osnovno stran z tujimi novicami
def scrape_24ur_tujina():
    result = []
    for index in range(1, 2):
        url = f"https://www.24ur.com/arhiv/novice/tujina/?p={index}"
        soup = req(url)
        scraped_articles = []
        main = soup.find('main')
        articles = main.find_all('a', class_='flex flex-col lg:flex-row wrap overflow-visible lg:overflow-hidden card-overlay pb-16 group') # type: ignore

        for article in articles:
            
            # Preverimo, ali obstajata naslov in href atribut.
            if 'href' in article.attrs: # type: ignore
                article_data = {
                    'link': URL + article['href'] # type: ignore
                }
                scraped_articles.append(article_data)

        result.append(singleScrape(scraped_articles))

        # Izpiši zbrane podatke v JSON formatu na standardni izhod (stdout).
    res = json.dumps(result, indent=4, ensure_ascii=False)

    print(res)
    return res


def singleScrape(scraped_articles: list):
    articles = []
    for link in scraped_articles:
        print(link['link'])
        soup = req(link['link'])
        summary = soup.find('p', class_='text-article-summary').text # type: ignore

        body = soup.find('div', class_='article__body') # type: ignore
        text: list = body.find_all('p') # type: ignore

        for index, item in enumerate(text):
            text[index] = item.text
        deli = '  '
        txt = deli.join(text) # type: ignore

        article_data = {
            'link': link['link'], # type: ignore
            'summary': str(summary),
            'text': txt
        }
        articles.append(article_data)
    return articles


scrape_24ur_tujina()
