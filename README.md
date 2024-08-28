# Задание 1

MySQL запрос

SELECT 
    u.id, 
    CONCAT(u.first_name, ' ', u.last_name) AS Name, 
    GROUP_CONCAT(DISTINCT b.author) AS Author,
    GROUP_CONCAT(DISTINCT b.name ORDER BY b.name) AS Books
FROM 
    users u
JOIN 
    user_books ub ON u.id = ub.user_id
JOIN 
    books b ON ub.book_id = b.id
WHERE 
    YEAR(CURDATE()) - YEAR(u.birthday) BETWEEN 7 AND 17
GROUP BY 
    u.id, u.first_name, u.last_name
HAVING 
    COUNT(DISTINCT b.author) = 1 AND
    COUNT(ub.book_id) >= 2 AND
    MAX(DATEDIFF(ub.return_date, ub.get_date)) <= 14;

# Задание 2

Инструкция по развертыванию и тестированию:

docker-compose up -d. Но он у меня не смог подняться нормально, чтобы не выскакивало ошибок, связанных с тем что на сервере не хватает места, или чего-то не хватает :(

Потому, сейчас сейчас есть рабочая тестовая версия на VPS.
Приложил файл Postman с коллекцией запросов(Coinrate Test Perfect Panel.postman_collection.json) в репозитории для тестирования запросов - rates,convert

http://166.1.201.227/api/v1?method=rates
http://166.1.201.227/api/v1


CURL запрос для rates
```
curl --location 'http://166.1.201.227/api/v1?method=rates' \
--header 'Authorization: Bearer yD2=qao8]V1f0%.Zq>3cH~f}F@wM:8GA#v}w6iT5oeDCikkgM2YZjL#E*=D0UM]f' \
--header 'Cookie: _csrf=4775ae63ee833d1d9f37a2d75ded02357af654dab0a0e15f33cbd31bf7fb1e38a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22K8r8D1IQyLX1KTYIzOqnSwPYnmnulCoZ%22%3B%7D'
```

CURL запрос для convert
```
curl --location 'http://166.1.201.227/api/v1' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer yD2=qao8]V1f0%.Zq>3cH~f}F@wM:8GA#v}w6iT5oeDCikkgM2YZjL#E*=D0UM]f' \
--header 'Cookie: _csrf=4775ae63ee833d1d9f37a2d75ded02357af654dab0a0e15f33cbd31bf7fb1e38a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22K8r8D1IQyLX1KTYIzOqnSwPYnmnulCoZ%22%3B%7D' \
--data '{
    "method": "convert",
    "currency_from": "BTC",
    "currency_to": "USD",
    "value": 1
}'
```