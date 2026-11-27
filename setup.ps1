$ErrorActionPreference = "Stop"

if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
}
if (-not (Test-Path "database/database.sqlite")) {
    New-Item "database/database.sqlite" -ItemType File | Out-Null
}

Write-Host "[1/6] Installing PHP dependencies..." -ForegroundColor Cyan
composer install
Write-Host "[2/6] Generating application key..." -ForegroundColor Cyan
php artisan key:generate
Write-Host "[3/6] Running migrations and demo seeders..." -ForegroundColor Cyan
php artisan migrate --seed
Write-Host "[4/6] Installing Node dependencies..." -ForegroundColor Cyan
npm install
Write-Host "[5/6] Building frontend..." -ForegroundColor Cyan
npm run build
Write-Host "[6/6] Done. Start the app with: php artisan serve" -ForegroundColor Green
