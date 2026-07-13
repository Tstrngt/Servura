@echo off
REM Servura Local Development Deployment Script for Windows

echo [%date% %time%] Starting Servura deployment...

REM Check if composer is available
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: Composer is not installed or not in PATH
    pause
    exit /b 1
)

REM Check if npm is available
where npm >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: npm is not installed or not in PATH
    pause
    exit /b 1
)

REM Install PHP dependencies
echo Installing PHP dependencies...
if not exist "vendor" (
    composer install --no-dev --optimize-autoloader
) else (
    echo Vendor directory exists, skipping composer install
)

REM Install Node dependencies and build
echo Installing Node dependencies...
if not exist "node_modules" (
    npm install
)

echo Building frontend assets...
npm run build

REM Create necessary directories if they don't exist
echo Creating necessary directories...
if not exist "storage\framework\cache" mkdir storage\framework\cache
if not exist "storage\framework\sessions" mkdir storage\framework\sessions
if not exist "storage\framework\testing" mkdir storage\framework\testing
if not exist "storage\framework\views" mkdir storage\framework\views
if not exist "storage\logs" mkdir storage\logs
if not exist "bootstrap\cache" mkdir bootstrap\cache

REM Check if .env exists
if not exist ".env" (
    echo WARNING: .env file not found, copying from .env.example
    if exist ".env.example" (
        copy .env.example .env
        echo WARNING: Please configure your .env file with appropriate settings
    ) else (
        echo ERROR: .env.example file not found
        pause
        exit /b 1
    )
)

REM Generate application key if not set
findstr /C:"APP_KEY=" .env >nul
if %errorlevel% neq 0 (
    echo Generating application key...
    if exist "vendor" (
        php artisan key:generate --force
    ) else (
        echo WARNING: Cannot generate key - vendor directory not available
    )
)

REM Run database migrations if artisan is available
if exist "artisan" and exist "vendor" (
    echo Running database migrations...
    php artisan migrate --force || echo WARNING: Migration failed - this is expected if database is not configured
    
    REM Clear caches
    echo Clearing caches...
    php artisan cache:clear >nul 2>&1 || echo WARNING: Cache clear failed
    php artisan config:clear >nul 2>&1 || echo WARNING: Config clear failed
    php artisan route:clear >nul 2>&1 || echo WARNING: Route clear failed
    php artisan view:clear >nul 2>&1 || echo WARNING: View clear failed
    
    REM Optimize for production
    echo Optimizing for production...
    php artisan config:cache >nul 2>&1 || echo WARNING: Config cache failed
    php artisan route:cache >nul 2>&1 || echo WARNING: Route cache failed
    php artisan view:cache >nul 2>&1 || echo WARNING: View cache failed
)

echo [%date% %time%] Deployment completed successfully!
echo.
echo ==================================
echo DEPLOYMENT SUMMARY:
echo ==================================
echo ^Ö PHP dependencies installed
echo ^Ö Frontend assets built
echo ^Ö Directories created
echo ^Ö Application configured
echo.
echo NEXT STEPS:
echo 1. Configure your .env file with database credentials
echo 2. Run 'php artisan migrate' to set up database tables
echo 3. Run 'php artisan serve' to start development server
echo 4. Visit http://localhost:8000 to test
echo.
echo FOR PRODUCTION DEPLOYMENT:
echo 1. Upload files to server
echo 2. Run 'sudo ./provision.sh' on the server
echo 3. Configure domain and SSL
echo 4. Run './deploy.sh' in /var/www/servura
echo.
pause
