# Servura threat model

Repository: `Tstrngt/Servura` (Laravel 11 / PHP 8.3). It has no separate infrastructure repository: the app runs on a single Ubuntu VPS set up by `provision*.sh` and deployed by hand (`git pull` + `artisan optimize:clear`).

## System overview and trust boundaries
- One Laravel app serves the public site, the customer portal (`/klant`), the staff portal (`/admin`), docs (`docs.servura.nl` and `/docs`) and a small API (`routes/api.php`).
- Roles are in `users.role`: `customer`, `employee`, `admin`, `owner`. The `admin` middleware (`app/Http/Middleware/Admin.php`) allows any staff role. Spatie permissions narrow staff further (`RolesAndPermissionsSeeder`). `Gate::before` grants owners everything (`AuthServiceProvider`). Owner-only screens are checked with `isOwner()`: billing, Openprovider/TransIP settings, TLDs, domain registrations, security settings.
- Outbound trust boundaries: the DirectAdmin servers (`ServerConnection`, encrypted password; `DirectAdminClient`, `ProvisioningService`), the registrars Openprovider and TransIP (`app/Services/Domains/*`; credentials in `BillingSetting`), Mollie (`MolliePaymentService`), and SMTP.

## Components
| Component | Code |
|---|---|
| Public site | `HomeController`, `ServiceController`, `CheckoutController` + `CheckoutCart`, `QuoteBuilderController`, `ContactController`, `NewsletterController`, `LegalController` (abuse form), `DocsController`, `Api\DomainController`, `resources/views` for these |
| Auth | `app/Http/Controllers/Auth/*`, `ImpersonationController`, `app/Http/Middleware/*`, `User` model, `AuthServiceProvider` |
| Customer portal | `app/Http/Controllers/Customer/*`, `NotificationController`, `PaymentController`, `resources/views/customer` |
| Admin portal | `app/Http/Controllers/Admin/*`, `resources/views/admin` |
| Payments & billing | `MollieWebhookController`, `MolliePaymentService`, `InvoiceService`, `InvoiceDocumentService`, `RenewalService`, `DunningService`, `TaxService` |
| Domains & DNS | `app/Services/Domains/*`, `DomainRegistrationService`, `DomainDnsService`, `DomainService`, `app/Jobs/*Domain*`, related console commands |
| Hosting provisioning | `DirectAdminClient`, `ProvisioningService`, `DomainHostingService`, `ServerConnection` |
| Infrastructure & deploy | `provision*.sh`, `deploy.sh`, `deploy.bat`, `setup-storage.sh`, `backup-mysql.sh`, `fix-cache.php`, `config/`, `.env.example` |

## Entry points and who can reach them
- Anonymous: checkout/cart (`/bestellen/*`), quote builder (captcha, throttle 10/min), contact (captcha), newsletter (throttle) and unsubscribe token, abuse form (throttle 5/min), `/api/domains/check` (throttle 30/min), login (rate-limited), password reset, email verification token, locale switch, docs, and `POST /mollie/webhook`. The webhook has no auth and fetches the payment from the Mollie API by id.
- Customer (logged in): profile and logo upload, services (cancel, transfer, upgrade, DirectAdmin password reset, DirectAdmin auto-login, which shows the stored DirectAdmin password), domains (DNS, nameservers, holder, authcode, auto-renew, internal transfer by token, link hosting), tickets with attachments, invoices and payments, quotes. Ownership checks are per controller (`authorizeView`: `user_id === Auth::id()`).
- Staff: everything under `/admin`, including impersonation (`customers.impersonate`), mail templates, invoice design, and the customer data reset.

## Assets (user: all equally important)
- DirectAdmin server credentials and customer hosting accounts (`external_password`).
- Registrar API credentials, and control over customer domains (authcodes, DNS, holder contacts, transfers).
- Payments and invoices: Mollie keys, invoice status (paid/credit), payment batches.
- Customer personal data: profiles, WHOIS contacts, tickets and attachments, invoices.

## Attackers (user: all matter)
- Anonymous internet users.
- Logged-in customers reaching other customers' data or services (IDOR, privilege escalation to staff).
- Non-owner staff: listed as an attacker, but see out of scope below.

## Out of scope / accepted risk (user)
- **Staff are fully trusted: owners, admins and employees.** Findings that require a logged-in staff account, or that only let one staff role reach another staff role's functions (e.g. employee to owner-only settings), are accepted risk. This covers stored XSS or template injection reachable only from admin input, SSRF through admin-configured server URLs, and the like. It does not cover a customer or anonymous user becoming staff, or anything a customer can trigger that runs in a staff browser (e.g. XSS in ticket content viewed by staff).

## Existing controls
- CSRF (`VerifyCsrfToken`), Laravel's hashed passwords, login rate limiting, captcha on the contact and quote forms, throttles on public forms, encrypted casts for server passwords and some settings, `AuditLog` records for impersonation and staff changes, and impersonation limited to active customers.

## Open questions
- Is the docs subdomain wildcard (`docs.{domain}`) intended?
- Should Mollie webhooks be verified beyond the API re-fetch?
