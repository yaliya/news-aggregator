# News Aggregator Backend

## Overview

Laravel 12-based backend for a personalized news aggregation platform. It ingests articles from multiple external APIs, normalizes and stores them in PostgreSQL, and exposes a JWT-protected REST API for searching, filtering, and retrieving personalized user feeds.

## Architecture Decisions

- **Framework**: Laravel 12 (modern bootstrap, configuration-based middleware & scheduling).
- **Database**: PostgreSQL 16 with JSONB for user preferences and full-text search on `articles`.
- **Queues**: Redis-backed queues for fetching news in the background, orchestrated via scheduled jobs.
- **Auth**: JWT-based stateless authentication suitable for SPAs and mobile clients.
- **Search**: PostgreSQL full-text search plus `spatie/laravel-query-builder` for flexible filters and sorting.

## Tech Stack

- **Language**: PHP 8.3 (compatible with Laravel 12 requirements in this environment)
- **Framework**: Laravel 12.x
- **Database**: PostgreSQL 16
- **Cache / Queue**: Redis
- **HTTP Client**: Guzzle
- **Queues Monitoring**: Laravel Horizon (dependency declared, dashboard wiring left for later)
- **Containerization**: Docker + docker compose + Nginx

## Prerequisites

- Docker and Docker Compose
- Make (optional, for convenience commands)

## Installation

```bash
cp backend/.env.example backend/.env
docker compose build
docker compose up -d
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

## API Key Setup

Set the following environment variables in `backend/.env`:

```bash
NEWSAPI_KEY=your_newsapi_key
GUARDIAN_KEY=your_guardian_key
NYTIMES_KEY=your_nytimes_key
JWT_SECRET=your_generated_jwt_secret
```

Recommended: generate `JWT_SECRET` via `php artisan jwt:secret` in a fully configured PHP environment.

## Running the Application

- **Start stack**: `make up`
- **View logs**: `make logs`
- **Run migrations**: `make migrate`
- **Run tests**: `make test`

Application is exposed at `http://localhost:8000`.

## API Documentation (high level)

- **Auth**
  - `POST /api/auth/register`
  - `POST /api/auth/login`
  - `POST /api/auth/logout`
  - `POST /api/auth/refresh`
  - `GET  /api/auth/me`
- **Articles**
  - `GET /api/articles`
  - `GET /api/articles/{id}`
  - `GET /api/articles/search`
- **User**
  - `PUT /api/user/preferences`
  - `GET /api/user/feed`

Common query parameters:

- `search=keyword`
- `source=newsapi,guardian,nytimes`
- `category=business,technology`
- `author=John Doe`
- `date_from=YYYY-MM-DD`
- `date_to=YYYY-MM-DD`
- `page=1`
- `per_page=20`

## Testing

```bash
make test
```

Feature, unit, and integration test skeletons can be added under `tests/Feature` and `tests/Unit` to cover:

- Authentication and preference flows
- Article listing, filtering, search
- Personalized feed behavior
- Queue jobs for article fetching

## Queue Management

- Scheduler enqueues `FetchArticlesJob` every 5 minutes.
- `FetchArticlesJob` dispatches per-source `FetchFromSourceJob` jobs on queues: `newsapi`, `guardian`, `nytimes`.
- Queue worker is provided via the `queue-worker` service in `docker-compose.yml`.

## Future Improvements

- Horizon dashboard wiring for queue monitoring.
- More sophisticated relevance ranking on the personalized feed.
- Additional observability (metrics, tracing) and API error envelopes.

