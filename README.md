# The Wager

A social wagering platform where friends create prediction markets, place bets on outcomes, and compete for coins. Built with Laravel 12, Alpine.js, and Tailwind CSS.

---

## Features

**Wagers**
- Create public or private wager events with custom choices and a closing time
- Join wagers, place bets on one or more outcomes, and watch the live pot update in real time
- Invite friends via token-based links
- Creator ends the wager by picking the winning choice — payouts distributed automatically
- Live pie chart showing bet distribution across choices
- Per-choice player breakdown (who bet what)
- In-wager chat with 2-second polling so players can trash talk while betting

**Social**
- Friend requests, friend list, and public user profiles
- Money transfers between friends (send, accept, decline)
- Dashboard notifications for pending invitations, transfers, and friend requests

**Balance**
- Every user starts with coins and can claim a free daily bonus every 3 hours
- Balance updates live across the UI after each action

**Coinflip**
- 50/50 mini-game against the house — double or nothing
- Animated 3D coin with separate heads and tails spin animations
- Game history showing last 10 flips

**Leaderboard**
- Four tabs: most coins won, best win rate, most wagers played, richest players

**Cosmetics**
- Shop with purchasable frames, titles, themes, and charms at different rarities (common → legendary)
- Equip and swap cosmetics from your profile

**Admin**
- User management: edit, delete, ban (with duration + reason), unban, zero balance
- Wager management: edit, delete, force-end
- Cosmetic management: full CRUD for the shop catalogue

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2+, Laravel 12 |
| Auth | Laravel Breeze |
| Frontend | Alpine.js 3, Tailwind CSS 4, Vite 7 |
| Charts | Chart.js |
| Database | PostgreSQL (SQLite compatible) |
| Testing | Pest 4 |
| Dev tooling | Laravel Sail, Laravel Pint, Laravel Pail |

---

## How to Run Locally

### Requirements

- PHP 8.2+
- Node.js 18+
- Composer 2+
- PostgreSQL (or SQLite for local dev)

---

### Installation Steps

**1. Clone the repository**
```bash
git clone <repo-url>
```

**2. Navigate to the project directory**
```bash
cd The-Wager
```

**3. Copy the example environment file**
```bash
cp .env.example .env
```

**4. Install dependencies**

PHP packages:
```bash
composer install
```

Node packages:
```bash
npm install
```

**5. Generate an application key**
```bash
php artisan key:generate
```

**6. Configure your database credentials**

Open the `.env` file and fill in your database details:

```
DB_CONNECTION=pgsql
DB_HOST=
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

**7. Run migrations and seed the database**

This will create all tables and populate the cosmetics shop with initial data:
```bash
php artisan migrate --seed
```

**8. Start the development server**
```bash
composer run dev
```

This starts the Laravel server, queue worker, and Vite hot-reload concurrently.

> If the above doesn't work, run these separately:
> ```bash
> php artisan serve
> npm run dev
> ```

Open [http://localhost:8000](http://localhost:8000)

---

## Admin Access

```
Email:    Admin@gmail.com
Password: admin123
```

The admin panel is available at `/admin`.

---

## Running Tests

```bash
composer run test
```

Uses Pest with the Laravel plugin. Test files live in `tests/Feature/`.

---

## Project Structure

```
app/
  Http/
    Controllers/       # WagerController, CoinflipController, AdminController, ...
    Middleware/        # AdminMiddleware, CheckBanned
  Models/              # Wager, User, WagerBet, Cosmetic, ...
database/
  migrations/          # All migrations, ordered chronologically
  seeders/             # CosmeticsSeeder — populates the shop
resources/
  views/
    wagers/            # Lobby, detail, create, edit, results, chat
    Admin/             # User/wager/cosmetic management tables
    layouts/           # app.blade.php, sidebar.blade.php
routes/
  web.php              # All application routes
  auth.php             # Breeze auth routes
tests/
  Feature/             # WagerCreatingTest, FriendTest, SidebarTest, ...
```
<<<<<<< HEAD
=======

---

>>>>>>> 8ae84cc24ca7ddd88efe57a9558fd370ca1d0727
