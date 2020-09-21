# Real Estate

### Installation guide

1. Start by cloning the project from repo.
2. Install project dependencies using command: 

   ```bash
    composer install
    ```
   
    or
    
    ```bash
    php composer.phar install
    ```      

3. Create a database with default encoding `utf8_general_ci`.

4. Copy `.env.example` file to `.env` inside project root:

    ```bash
    cp .env.example .env
    ```

5. Fill `.env` with the correct database information:

    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6. Fill `.env` with the correct project URL:

    ```bash
    APP_URL=http://localhost
    ```
   
7. Run `php artisan key:generate`

8. Run `php artisan migrate`

9. Run `php artisan db:seed`

10. Run `php artisan storage:link`

11. Install cron task:

    ```bash
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
    ```
    
12. Add command `php /path-to-your-project/artisan queue:work sqs --sleep=3 --tries=3` to Supervisor config. Additional information is here https://laravel.com/docs/7.x/queues#supervisor-configuration 

13. Set file and folder permissions and ownership (username and group may differ):

    ```bash
    sudo chown -R www-data:www-data /path-to-your-project
    sudo find /path-to-your-project -type d -exec chmod 755 {} \;
    sudo find /path-to-your-project -type f -exec chmod 664 {} \;
    sudo chgrp -R www-data /path-to-your-project/storage /path-to-your-project/bootstrap/cache
    sudo chmod -R ug+rwx /path-to-your-project/storage /path-to-your-project/bootstrap/cache
    ```
14. Install npm modules:

    ```bash
    npm install
    ```
15. Compile js and css:

    ```bash
    npm run production
    ```
    For dev environment use 
    ```bash
    npm run dev
    ```
    
### Example of Nginx Server Configuration

```bash
server {
    listen 80;
    server_name example.com;
    root /example.com/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
``` 

> More details can be found here https://laravel.com/docs/7.x/deployment.    
    
### Update guide

1. Update project dependencies using command: 

   ```bash
    composer install
    ```
   
    or
    
    ```bash
    php composer.phar install
    ```
   
2. Run `php artisan config:clear`

3. Run `php artisan migrate`
         
4. Run `php artisan cache:clear`

5. Reload and then restart Supervisor: 

   ```bash
    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl restart all
    ```
6. Recompile js and css: 

   ```bash
    npm run production
    ```
    For dev environment use 
    ```bash
    npm run dev
    ```
