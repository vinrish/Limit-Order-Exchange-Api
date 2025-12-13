# Limit Order Exchange API

A **Limit Order Exchange** system that allows users to place, manage, and track buy/sell orders for cryptocurrencies. Built with **Laravel**, **Sanctum**, **MySQL**, and **Vue.js** for a full-stack modern trading experience.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Environment Setup](#environment-setup)
- [API Endpoints](#api-endpoints)

---

## Features

- **User Authentication & Authorization**
    - Secure user registration and login using Laravel Sanctum.
    - Session-based and API token authentication.

- **Wallet & Balances**
    - Users can view their USD and crypto asset balances.
    - Real-time wallet updates on order execution.

- **Order Management**
    - Place **Buy** or **Sell** limit orders.
    - View **Open Orders**, **Filled Orders**, and **Cancelled Orders**.
    - Cancel pending orders.

- **Order Book**
    - Real-time order book displaying **Bids** and **Asks** for selected symbols.
    - Sorted by price for easy visualization.

- **Trade Matching**
    - Automatic order matching engine.
    - Updates order status to Filled and adjusts balances accordingly.

- **Notifications**
    - Real-time notifications when an order is executed or cancelled.

---

## Tech Stack

- **Backend:** Laravel (PHP 8.4)
- **Authentication:** Laravel Sanctum
- **Database:** MySQL
- **Frontend:** Vue.js 3
- **Realtime:** pusher & Laravel Echo

---

## Installation

### Backend

```bash
git clone https://github.com/vinrish/Limit-Order-Exchange-Api.git
cd limit-order-exchange-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

```

### Frontend
```bash
cd limit-order-exchange-api/frontend
yarn
cp .env.example .env
yarn dev

```

### Environment Setup
#### Backend
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=limit_order
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=
PUSHER_PORT=
PUSHER_SCHEME=

```
#### Frontend
```
VITE_API_BASE_URL=
VITE_PUSHER_APP_KEY=
VITE_PUSHER_APP_CLUSTER=
VITE_PUSHER_APP_PORT=
VITE_PUSHER_APP_SCHEME=
VITE_API_URL=

```

## Screenshots

### LoginView
![LoginView](projectscreenshots/loginview.png)

### RegisterView
![LoginView](projectscreenshots/registerview.png)

### Wallet Overview
![Wallet Overview]("projectscreenshots/orders%20wallet%20overview.png")



### Limit Order Form
![Limit Order Form](projectscreenshots/limit%20order%20form.png)


## API Endpoints
Auth
 - **POST /api/v1/register** - Register a new user
 - **POST /api/v1/login** - Login user
 - **POST /api/logout** - Logout user

Wallet
- **GET /api/v1/profile** - Get user wallet & balances

Orders
- **POST /api/orders** - Place a new order
- **GET /api/orders** - Fetch order book
- **GET /api/all-orders** - Fetch user orders
- **POST /api/orders/{id}/cancel** - Cancel an order

