# Инструкция по запуску проекта
1. **Клонировать репозиторий**

```bash
git clone https://github.com/Croki555/hotellink.git ~/Desktop/hotellink
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

### Получение списка комнат
- **Все комнаты**  
`GET` `http://localhost:80/api/rooms`

- **Доступные комнаты на период (пример)**  
`GET` `http://localhost:80/api/rooms?start_date=2025-07-10&end_date=2025-07-13`

### Бронирование
`POST` `http://localhost:80/api/booking`

**Пример запроса:**
```json
{
  "client_id": 5,
  "room_number": 111,
  "check_in": "2025-07-12 20:35",
  "check_out": "2025-07-12 19:35"
}
```

## Важные условия бронирования
- Требуется указать `client_id` (так как нет системы авторизации)
- Минимальное время брони - 6 часов
- Бронировать можно не позднее чем за 1 час до заезда

## Статусы
2 статуса Забронировано и Свободно, в дальнейшем можно и больше добавить + будет больше функционала
