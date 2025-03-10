Clone the repository:

git clone [[https://github.com/your-repo.git](https://github.com/nslly/aas-laravel-exam.git)](https://github.com/nslly/aas-laravel-exam.git)
cd your-repo

Install dependencies:

composer install

Copy the .env.example file and update environment variables:

cp .env.example .env

Update database credentials in .env file:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aas_laravel
DB_USERNAME=root
DB_PASSWORD=

Generate the application key:
php artisan key:generate

Run migrations database:
php artisan migrate


Running the Application

Start the local development server:
php artisan serve
