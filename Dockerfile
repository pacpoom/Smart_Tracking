# File: Dockerfile
# คำอธิบาย: พิมพ์เขียวสำหรับสร้าง Image ของ Laravel App

# 1. ใช้ Base Image เป็น PHP 8.2-FPM (อัปเดตจาก 8.1)
FROM php:8.2-fpm

# 2. ตั้งค่า Working Directory
WORKDIR /var/www

# 3. ติดตั้ง Dependencies ที่จำเป็นสำหรับระบบและ Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# 4. ล้าง Cache ของ apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 5. ติดตั้ง PHP Extensions ที่ Laravel ต้องการใช้
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 6. ติดตั้ง Composer (ตัวจัดการ Package ของ PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. คัดลอกไฟล์ทั้งหมดในโปรเจกต์เข้าไปใน Image
COPY . .

# 8. กำหนดสิทธิ์การเข้าถึงไฟล์และโฟลเดอร์
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 9. Expose port 9000 สำหรับ PHP-FPM
EXPOSE 9000

# 10. คำสั่งที่จะรันเมื่อ Container เริ่มทำงาน
CMD ["php-fpm"]