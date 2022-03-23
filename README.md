# Микросервис получения новостей по теме криптовалют

За основу приложения взят фреймворк [Lumen](https://lumen.laravel.com/docs/9.x)
Для удобства развёртывания, добавлен пакет [asavenkov/sail-lumen](https://packagist.org/packages/asavenkov/sail-lumen)
В приложении используется сервис-репозиторный подход

## Запуск микроосервиса

1. Для запуска необходим установленный, [Docker Compose](https://docs.docker.com/compose/install/)
2. Установите зависимости `composer install --ignore-platform-reqs` с игнорированием требований в случае, если локальная версия php ниже требуемой
3. Создадим алиас для команды sail `alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail`
4. Перед запуском контейнеров необходимо заполнить переменные окружения в файле .env по примеру [.env.example](.env.example)
5. Запускаем контейнеры `sail up -d` Также можно воспользоваться [Makefile](Makefile) при условии если у вас установлен [make](https://www.gnu.org/software/make/)
6. После запуска контейнеров необходимо выполнить миграции `sail artisan migrate`

## Работа микроосервиса

### Получение данных из внешнего API с новостями
* В базовый образ [Dockerfile](docker/8.1/Dockerfile) добавлен демон crontab c [расписанием](docker/8.1/scheduler)
* crontab запускается supervisor в качестве [worker](docker/8.1/supervisord.conf) 
* в [Kernal](app/Console/Kernel.php) реализуется запуск команды [AddNewArticlesByThemes](app/Console/Commands/AddNewArticlesByThemes.php) по расписанию из настроек
* команда [AddNewArticlesByThemes](app/Console/Commands/AddNewArticlesByThemes.php) берёт из настроке темы для парсинга и в цикле добавляет [AddNewArticleJob](app/Jobs/AddNewArticleJob.php) c темой в очередь
* Далее происходи вызов команды обработки очереди `Artisan::call('queue:work --stop-when-empty --daemon')` Драйвером очереди настроен Redis
* [AddNewArticleJob](app/Jobs/AddNewArticleJob.php) обращается к сервису добавления статей [ArticleService](app/Services/ArticleService.php), который используя сервис [GetArticlesFromApiService](app/Repositories/GetArticlesFromApiService.php) и репозиторий [ArticleRepository](app/Repositories/ArticleRepository.php) получает статьи из внешнего API и сохраняет их в базу данных
* В случае если в базе данных уже есть статьи с идентичной темой, то данная статья не сохраняется


### Предоставление данных
* Микросервис получает запросы по GET маршруту: `api/v1/articles`
* В качестве GET параметров можно передать следующие фильтры:
```
    source=Reuters          // фильтр по источнику новостей, может не передаваться
    theme=bitcoin           // фильтр по теме статьи, может не передаваться
    published_at=2022/03/02 // фильтр по дате опубликования статьи, может не передаваться, только дата

```
* При неверном формате фильтра могут возникнуть ошибки валидации, например:
```
{
    "error_type": "Validation errors",
    "errors": {
        "published_at": [
            "The published at is not a valid date."
        ]
    }
}
```

* Дополнительно есть возможность поиска по подстроке в атрибутах статьи theme, author, title, description, content, source
```
    search_in=title_substring // Где title это название атрибута а substring - искомая подстрока
```
* При несоблюдении данного синтаксиса могут возникать ошибки валидации, например:
```
{
    "error_type": "Validation errors",
    "errors": {
        "search_in": [
            "Field value in not in accepted list"
        ]
    }
}
```

* Ответ на запрос отправляется а виде json с ключем `result`
```
{
    "result": [
        {
            "theme": "litecoin",
            "author": null,
            "title": "EU checking if cryptoassets being used to bust Russian sanctions - EU official - Reuters",
            "description": "The European Commission is studying whether cryptoassets are being used to get round financial sanctions imposed on Russian banks following the country's invasion of Ukraine, a senior European Union official said on Wednesday.",
            "url": "https://www.reuters.com/technology/eu-checking-if-cryptoassets-being-used-bust-russian-sanctions-eu-official-2022-03-02/",
            "image_url": "https://www.reuters.com/resizer/Vy3OPpFOIGA6zpWKlo4HR7HmJDY=/1200x628/smart/filters:quality(80)/cloudfront-us-east-2.images.arcpublishing.com/reuters/2FHQFCQIZJK6LEZTN6S6YS34KY.jpg",
            "published_at": "2022-03-02 11:03:00",
            "content": "Representations of cryptocurrencies Bitcoin, Ethereum, DogeCoin, Ripple, and Litecoin are seen in front of a displayed Binance logo in this illustration taken, June 28, 2021. REUTERS/Dado Ruvic/Illus… [+1327 chars]",
            "source": "Reuters"
        },
        //...
    ]
}
```

### Настройки микросервиса

* В настройках можно изменить api - поставщика данных для микросервиса а также время кеширования в .env:

```
#Базовый url для внешнего api, например https://newsapi.org/v2/everything
NEWS_API_BASE_URL=

#Ключ api
NEWS_API_KEY=

#Язык на котором необходимо получить ответ, по умолчанию стоит "ru"
NEWS_API_RESULT_LANG=ru

#Темы, по которым необходимо получить новости, разделяются запятой: 
#"bitcoin,litecoin,ripple,dash,ethereum"
ARTICLES_THEMES=

#Задержка в минутах между запросами к внешнему api для получения новостей
#Миниальным знаенчением является 1 раз в минуту (настройка crontab)
PER_ARTICLE_REQUEST_DELAY=

```
