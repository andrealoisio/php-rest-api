# php-rest-api


### Call php to interpret a file
```
docker exec -it -w "/app/public" php php random-user.php
```

### Install composer dependency
```
docker exec -it -w "/app" php composer update
docker exec -it -w "/app" php composer require catfan/medoo
```


### Conecting and creating database
```
docker exec -it mysql bash
mysql -uapi -p
*****
use api
mysql> CREATE TABLE task (
    -> id INT NOT NULL AUTO_INCREMENT,
    -> name VARCHAR(128) NOT NULL,
    -> priority INT DEFAULT NULL,
    -> is_completed BOOLEAN NOT NULL DEFAULT FALSE,
    -> PRIMARY KEY (id),
    -> INDEX (name)
    -> );
describe task;
show indexes from task;
```

