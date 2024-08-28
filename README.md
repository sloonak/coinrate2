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


Приложил файл Postman с коллекцией запросов(Coinrate Test Perfect Panel.postman_collection.json) в репозитории для тестирования запросов - rates,convert, там можно поменять на localhost запросы если нужно, а так есть сервер в сети к которому можно обращаться

http://166.1.201.227/api/v1?method=rates
http://166.1.201.227/api/v1