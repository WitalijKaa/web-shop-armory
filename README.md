# web-shop-armory

## TEST TASK

Create a project for a simple e-commerce shopping cart system.

Users should be able to browse products, add them to a cart, update quantities, and remove items. Each product should have a name, price, and stock_quantity.

Tech Stack:
Backend: Laravel
Frontend: Inertia.js + React
Styling: Tailwind CSS
Version Control: Git/GitHub

Key Requirements:
Low Stock Notification: When a product's stock is running low, a Laravel Job/Queue should be triggered to send a notification (e.g., to an admin).

Daily Sales Report: Implement a scheduled job (cron) that runs every evening and sends a report of all products sold that day.

## video with project basic description

YouTube

- https://www.youtube.com/watch?v=fDBx6eTxzQg
- https://www.youtube.com/watch?v=lSWtNYTVmmw

Download
- https://drive.google.com/drive/folders/1Fdl6i8gsxv0P66xeTXS9tYebuPb6BFUf?usp=drive_link

## how to

- please clone repo
- mysql
- CREATE DATABASE armory;
- exit
- composer run-script setup
- php artisan ide-helper:generate
- php artisan serve
