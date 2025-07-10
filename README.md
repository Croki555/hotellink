# Инструкция по запуску проекта
1. **Клонировать репозиторий**

```bash
git clone https://github.com/Croki555/hotellink.git
```

2. **Настройка переменных окружения**  
   Переименовать файл `.env.example` в `.env`

3. **Запуск контейнеров**
```bash
docker-compose up -d
```
4. **Установка зависимостей**
```bash
docker-compose exec hotellink composer install 
```
   
5. **Инициализация базы данных**  
```bash
docker-compose exec hotellink php artisan migrate:fresh --seed
```

## API Endpoints

### Просмотр доступных номеров на период
- **start_date** - обязательно
- **end_date** - обязательно
- опционально добавлена пагинация (per_page, page)

`GET` `http://localhost:80/api/rooms?start_date=2025-07-10&end_date=2025-07-13`

### Просмотр список броней (заселены, не заселены, выезд)
`POST` `http://localhost:80/api/booking`


**Пример запроса:**
```json
{
  "client_id": 5,
  "room_number": 111,
  "check_in": "2025-07-12 20:35",
  "check_out": "2025-07-12 19:35", 
  "status": "not_occupied"
}
// Доступные статусы: occupied, not_occupied, check_out 
```

