### Instruction to set up
- clone repository
```bash
git clone https://github.com/agrism/sunfinance-test.git
```
- create .env file
```bash
cp .env.example .env
```
- get up docker containers
```bash
docker-compose up
```
- enter in container
```bash
docker exec -it {container} bash
```
- install dependencies
```bash
coposer install
```
- generate app key
```bash
php artisan key:generate
```
- run migrations
```bash
php artisan migrate
```
- run queue worker
```bash
php artisan queue:work
```

### Usage
- api available:
http://localhost/
- api documentation:
http://localhost/api/documentation
- MailHog:
http://localhost:8025/
- RabbitMQ:
http://localhost:15672/ (username: `admin`, password: `admin`)


### Live demo
- https://sunfinance.kilograms.lv
