# News Aggregator API

A Laravel REST API that pulls articles from NewsAPI, The Guardian, and The New York Times, normalizes them into a single feed, and lets users filter by source, category, author, and date — or build a personalized feed based on their preferences.

## Stack

- **PHP 8.3 / Laravel 12**
- **PostgreSQL** — article storage with full-text search
- **Redis** — queue driver and cache
- **JWT** (tymon/jwt-auth) — stateless auth
- **Spatie Query Builder** — filtering and sorting
- **Docker** — PHP-FPM, Nginx, Postgres, Redis, queue worker

## Getting Started

Check Makefile for supported commands.

```bash
cp .env.example .env
make up
make composer
make key
make install
make migrate
make seed
```
## Configuring sources

Sources are configured in news.php config file. Adjust env variables accordingly

```bash
NEWSAPI_ENABLED=true
NEWSAPI_ENDPOINT=https://newsapi.org/v2/top-headlines
NEWSAPI_KEY=

GUARDIAN_ENABLED=false
GUARDIAN_ENDPOINT=https://content.guardianapis.com/search
GUARDIAN_KEY=

NYTIMES_ENABLED=false
NYTIMES_ENDPOINT=https://api.nytimes.com/svc/search/v2/articlesearch.json
NYTIMES_KEY=
```
Then run the scheduler to trigger the job

```bash
php artisan schedule:run
```
We also need to process the dispatched queues

```bash
php artisan queue:work
```

## API

### Auth

```
POST /api/auth/register
POST /api/auth/login
POST /api/auth/logout
POST /api/auth/refresh
GET  /api/auth/me
```

### Articles

```
GET /api/articles
GET /api/articles/{id}
```

Supported query params:

```
?search=keyword
?source=newsapi,guardian
?category=business,tech
?author=John Doe
?date_from=2024-01-01
?date_to=2024-12-31
?per_page=20
?sort=-published_at
```

### Feed & Preferences

```
GET /api/user/feed
PUT /api/user/preferences
```

Preferences shape:

```json
{
  "source": ["newsapi", "guardian"],
  "category": ["technology"],
  "author": ["John Doe"]
}
```

### Response Format

```json
{
  "data": [
    {
      "id": 1,
      "source": "newsapi",
      "title": "Article Title",
      "description": "Brief description",
      "content": "Full content",
      "author": "John Doe",
      "category": "technology",
      "url": "https://...",
      "image_url": "https://...",
      "published_at": "2024-02-16T10:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 150,
    "last_page": 8
  },
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  }
}
```


## Architecture

Articles are fetched every 5 minutes via a scheduled job that dispatches per-source queue jobs. Each source implements `NewsSourceInterface` and handles its own transformation before the `ArticleTransformer` normalizes everything into a common shape. Duplicates are handled with an upsert on `source + source_id`.

```
Services/NewsAggregator/
  Contracts/NewsSourceInterface.php
  Sources/NewsApiSource.php
  Sources/GuardianSource.php
  Sources/NYTimesSource.php
  NewsAggregatorService.php
  ArticleTransformer.php
```

Filtering lives in a repository layer, controllers pass validated input down, repositories return query builders or paginators. The base repository handles all common filter logic; child repositories declare which filters they allow.


## Testing

```bash
make test
```

```
tests/Feature/Auth/RegisterTest.php
tests/Feature/Articles/ListArticlesTest.php
tests/Feature/Articles/UserFeedTest.php
```