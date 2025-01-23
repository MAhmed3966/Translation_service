# Translation Management API

This is an API-driven service designed to manage translations for multiple locales. It provides functionality for creating, updating, viewing, and searching translations, with support for context-based tagging and exporting translations for frontend applications like Vue.js. The API is designed with scalability in mind, allowing for handling a large number of translations efficiently.

## Features

- **Multi-Locale Support**: Store translations for multiple locales (e.g., `en`, `fr`, `es`) and extend the system to support new languages in the future.
- **Tagging Translations**: Tag translations for different contexts (e.g., `mobile`, `desktop`, `web`) to help with filtering and organizing.
- **CRUD Operations**: Expose endpoints to create, update, view, and search translations by tags, keys, or content.
- **JSON Export**: Export translations in a frontend-compatible format to be used by applications like Vue.js.
- **Performance**: Optimized for large datasets (100k+ records) and efficient data fetching.
- **Security**: Token-based authentication using Laravel Sanctum.
- **Docker Support**: Contains a Docker setup for easy development environment configuration.
- **Test Coverage**: Full test coverage (> 95%) for all major functionalities.

## API Endpoints

### 1. **Create Translation**
- **POST** `/api/translations`
- Create a new translation.
  
### 2. **Update Translation**
- **PUT** `/api/translations/{translationId}`
- Update an existing translation.

### 3. **Get Translations by Locale**
- **GET** `/api/translations/{locale}`
- Fetch all translations for a specific locale.

### 4. **Get Translation by Key or ID**
- **GET** `/api/translations/{identifier}`
- Fetch a translation by its key or ID.

### 5. **Search Translations**
- **GET** `/api/translations/search`
- Search for translations by key or value.

### 6. **Get Translations by Tag**
- **GET** `/api/translations/tags/{tag}`
- Get translations filtered by a specific tag.

### 7. **Assign Tags to Translation**
- **POST** `/api/translations/{translationId}/tags`
- Assign tags to a translation.

### 8. **Export Translations**
- **GET** `/api/translations/export`
- Export translations as a JSON file compatible with frontend applications.

## Requirements

- PHP >= 8.0
- Composer
- Laravel >= 9.x
- Docker (for local development setup)

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-username/translation-management-api.git
cd translation-management-api
