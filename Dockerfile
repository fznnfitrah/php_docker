# Gunakan php fpm agar cocok dengan konfigurasi Nginx Anda
FROM php:8.2-fpm 

# Install ekstensi yang dibutuhkan untuk database (PENTING!)
RUN docker-php-ext-install pdo pdo_mysql

# Set folder kerja
WORKDIR /var/www/html

# (Opsional) Salin file ke dalam container
# Tapi karena di docker-compose sudah di-mount (volume), baris COPY ini sebenarnya bisa dilewati untuk development
# COPY . /var/www/html/# Gunakan php fpm agar cocok denganj konfigurasi Nginx Anda
FROM php:8.2-fpm 

# Menambahkan instalasi paket ping
RUN apt-get update && apt-get install -y iputils-ping

# Install ekstensi yang dibutuhkan untuk database (PENTING!)
RUN docker-php-ext-install pdo pdo_mysql

# Set folder kerja
WORKDIR /var/www/html

# (Opsional) Salin file ke dalam container
# Tapi karena di docker-compose sudah di-mount (volume), baris COPY ini sebenarnya bisa dilewati untuk development
# COPY . /var/www/html/