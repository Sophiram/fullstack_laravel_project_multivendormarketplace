# ប្រើប្រាស់ PHP ជំនាន់ទី 8.2 ជាមួយ Apache (អាចដូរជំនាន់តាម Project របស់អ្នក)
FROM php:8.4-apache

# ដំឡើង System Dependencies ដែលចាំបាច់ (បន្ថែម libzip-dev)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# សម្អាត Cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# ដំឡើង PHP Extensions សម្រាប់ Laravel និង MySQL (បន្ថែម zip នៅខាងចុង)
RUN docker-php-ext-install pdo_mysql mbstring pcntl bcmath gd zip

# បើកដំណើរការ Apache mod_rewrite
RUN a2enmod rewrite

# ប្តូរទីតាំង DocumentRoot របស់ Apache ទៅកាន់ folder /public របស់ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# កំណត់ Working Directory
WORKDIR /var/www/html

# Copy កូដទាំងអស់ពីកុំព្យូទ័រទៅកាន់ Container
COPY . .

# ដំឡើង Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ដំណើរការ Composer ដើម្បីដំឡើង Packages របស់ Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev

# ដំណើរការ NPM ដើម្បី Build Frontend (Blade/Vite)
RUN npm install && npm run build

# ផ្តល់សិទ្ធិ (Permissions) ទៅឱ្យ Folder ដែលត្រូវសរសេរទិន្នន័យចូល
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy ឯកសារ Entrypoint និងផ្តល់សិទ្ធិឱ្យវាដំណើរការបាន (Executable)
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# បើក Port 80 សម្រាប់ Render
EXPOSE 80

# ដំណើរការ Entrypoint Script ពេល Container ចាប់ផ្តើម
ENTRYPOINT ["docker-entrypoint.sh"]
