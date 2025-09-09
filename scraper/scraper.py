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
    url = "https://www.24ur.com/novice/tujina"
    soup = req(url)
    scraped_articles = []
    articles = soup.find_all('a', attrs={'data-upscore-object-id': True})
    
    for article in articles:
        
        # Preverimo, ali obstajata naslov in href atribut.
        if 'href' in article.attrs: # type: ignore
            article_data = {
                'link': URL + article['href'] # type: ignore
            }
            scraped_articles.append(article_data)
    
    result = singleScrape(scraped_articles)

    # Izpiši zbrane podatke v JSON formatu na standardni izhod (stdout).
    res = json.dumps(result, indent=4, ensure_ascii=False)
    return res


def singleScrape(scraped_articles: list):
    articles = []
    for link in scraped_articles:
        print(link['link'])
        soup = req(link['link'])
        summary = soup.find('p', class_='text-article-summary')
        body = soup.find('div', class_='article__body')
        text: list = str(body.find_all('p')).strip('[]') # type: ignore

        article_data = {
            'link': link['link'], # type: ignore
            'summary': str(summary),
            'text': text
        }
        articles.append(article_data)
    return articles


if __name__ == '__main__':
    scrape_24ur_tujina()
