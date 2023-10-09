## Instruksi Instalasi

Aplikasi ini dibuat menggunakan Laravel 9, Vue 3, dan MySQL. Sebelum melakukan instalasi pastikan anda memiliki : 

- PHP >= 8.0
- Composer
- NodeJS >= 14.0
- NPM atau Yarn
- MySQL

## Instalasi
1. Silahkan lakukan clone repository dan masuk ke folder pretest
```
git clone https://github.com/putrabagas/pretest.git
cd pretest
```
2. Buat File .env
Lakukan duplikat file .env.example dan ganti nama salinannya menjadi .env. Kemudian sesuaikan konfigurasi database dan pengaturan lain jika diperluka.
3. Instal Dependencies PHP
```
composer install
```
4. Generate App Key Laravel
```
php artisan key:generate
```
5. Migrasi Database serta gunakan Seeder
```
php artisan migrate --seed
```
6. Instal Laravel/Passport
```
php artisan passport:install
```
7. Instal Dependencies JS
```
npm install 
```
8. Compile Asset Front-end
```
npm run dev
```
9. Jalankan Server
```
php artisan serve
```
10. Selesai
Silahkan buka browser dan akses aplikasi di url yang telah anda sesuaikan.

## Penggunaan
Untuk login sebagai admin silahkan menggunakan email dan password berikut :
```
email : admin@gmail.com
password : admin
```
Untuk membuat akun user, silahkan lakukan registrasi.


Terimakasih.
