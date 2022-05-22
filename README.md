# Inner Circle test

Solution of the test task for Inner Circle

## Setup

- run `docker compose up -d`
- enter into container `docker exec -it inner-circle-app bash`
- install packages `composer install`
- run migrations `bin/console d:m:m`
- load fixtures `bin/console d:f:l`

## How check solution?

I have created console command for checking.
Just call `bin/console ff:shortest:chain :start :end`,
where 
 - :start -> user id of member for begin search
 - :end -> user id of member for end search
