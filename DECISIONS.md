# Technische Beslissingen - Servura

## Stack Keuzes

### Backend Framework
**Keuze**: Laravel 11.x
**Redenen**:
- Bewezen, stabiel PHP framework met lange support (LTS)
- Ingebouwde auth systemen, ORM, migrations
- Goede security features (CSRF, XSS protection)
- Schaalbaar en onderhoudbaar
- Grote community en documentatie

### Database
**Keuze**: MySQL 8.0
**Redenen**:
- Bewezen performance voor webapplicaties
- Volledige ACID compliance
- Goede integratie met Laravel
- Betrouwbare backup tools

### Frontend
**Keuze**: Blade templates + Alpine.js voor interactiviteit
**Redenen**:
- Blade is native aan Laravel, geen build complexity
- Alpine.js voor lightweight interactiviteit zonder overkill
- Snelle Lighthouse scores mogelijk
- Geen complex Node.js build pipeline nodig

### Webserver
**Keuze**: Nginx
**Redenen**:
- Superieure performance vs Apache
- Goede PHP-FPM integratie
- Efficiënte static file serving
- SSL terminatie

### Deployment
**Keuze**: Git-based deployment met shell scripts
**Redenen**:
- Eenvoudig, betrouwbaar, reproduceerbaar
- Geen complexe CI/CD nodig voor dit project
- Volledige controle over deployment proces
- Makkelijk te troubleshooten

## Security Architectuur

### Authenticatie
- Laravel's built-in auth system met bcrypt hashing
- httpOnly, secure cookies
- Rate limiting op login endpoints
- Session fixation protection

### Data Protection
- Prepared statements (Eloquent ORM) tegen SQL injection
- CSRF tokens op alle POST requests
- Input sanitization en validation
- File upload security (type en size validatie)

### Server Security
- UFW firewall met alleen benodigde poorten
- Fail2ban voor brute force bescherming
- Automatische security updates
- SSL via Let's Encrypt met certbot

## Database Schema Principes

### Normalisatie
- 3NF normalisatie voor data integriteit
- Foreign key constraints voor relaties
- Indexes op veelgebruikte query's

### Audit Trails
- Created_at en updated_at timestamps
- User tracking voor belangrijke acties
- Soft deletes voor data herstel

## Performance Strategie

### Caching
- Redis voor session storage
- Laravel cache voor frequently accessed data
- Browser caching voor static assets

### Database Optimalisatie
- Query optimization met eager loading
- Database indexes op foreign keys
- Connection pooling

### Frontend Performance
- Minimal JavaScript (Alpine.js)
- CSS met Tailwind (gebruikt PurgeCSS)
- Image optimization
- Lazy loading waar nodig

## Email Configuratie

**Keuze**: SMTP via omgevingsvariabelen
**Redenen**:
- Flexibel - elke SMTP provider mogelijk
- Geen vendor lock-in
- Eenvoudige configuratie
- Betrouwbaar voor transactional email

## Backup Strategie

### Database Backups
- Dagelijkse automatische backups met mysqldump
- 14 dagen retentie
- Gecomprimeerd om ruimte te besparen
- Off-server backup optioneel

### File Backups
- Git repository als primary backup
- Periodieke file system backups

## Monitoring en Logging

### Logging
- Structured logging met Laravel
- Separate log levels voor development/production
- Geen sensitive data in logs
- Log rotation om disk space te beheren

### Monitoring
- Nginx access/error logs
- PHP-FPM status monitoring
- Database performance monitoring
- Uptime monitoring (optioneel)

## Toekomstbestendigheid

### Meertaligheid
- Laravel's localization system voorbereid
- Content vertaling via database
- URL structuur meertalig gemaakt

### Schaalbaarheid
- Stateless application design
- Database connection pooling
- Cache strategy voor horizontale schaalbaarheid
- Load balancer ready setup

### API Voorbereiding
- Controller separation voor future API endpoints
- Resource-based data structure
- Consistent response format

## Alternatieven Overwogen

### Symfony vs Laravel
Symfony is krachtig maar complexer. Laravel biedt betrouwbare features met snellere development.

### React vs Alpine.js
React is overkill voor deze applicatie. Alpine.js biedt voldoende interactiviteit zonder complexiteit.

### PostgreSQL vs MySQL
PostgreSQL heeft meer features maar MySQL is simpeler te beheren en voldoet aan alle requirements.

### Docker vs Native Installatie
Docker biedt consistentie maar voegt complexiteit toe. Native installatie is makkelijker te beheren voor single-server setup.
