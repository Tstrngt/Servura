<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\CustomerService;
use App\Models\Service;
use App\Models\User;
use App\Services\PortalTicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class QuoteBuilderController extends Controller
{
    public function index(Request $request)
    {
        $service = $request->filled('service')
            ? Service::where('slug', $request->input('service'))->where('is_active', true)->first()
            : null;

        $services = Service::where('is_active', true)->orderBy('title')->get(['id', 'slug', 'title']);
        $form = \App\Support\QuoteFormFields::resolve();

        return view('quote.builder', compact('service', 'services', 'form'));
    }

    public function loginPrompt(Request $request)
    {
        session(['url.intended' => route('quote.builder', ['service' => $request->input('service')])]);

        return redirect()->route('login')
            ->with('success', 'Log in om verder te gaan met uw offerte-aanvraag.');
    }

    public function store(Request $request, PortalTicketService $ticketService)
    {
        $validator = Validator::make($request->all(), [
            'service' => ['required', Rule::exists('services', 'slug')->where('is_active', true)],
            'goal' => 'required|string|max:255',
            'pages' => 'required|string|max:255',
            'visitors' => 'required|string|max:255',
            'design' => 'required|string|max:255',
            'current_website' => 'nullable|string|max:255',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'content' => 'nullable|array',
            'content.*' => 'string|max:255',
            'timeline' => 'required|string|max:255',
            'budget' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:5000',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => [Rule::requiredIf(fn () => ! Auth::check()), 'nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'service.required' => 'Kies waarvoor u een offerte aanvraagt.',
            'service.exists' => 'De gekozen dienst is niet beschikbaar.',
            'goal.required' => 'Geef aan wat het doel van uw website is.',
            'pages.required' => 'Geef het verwachte aantal pagina\'s op.',
            'visitors.required' => 'Geef het verwachte aantal bezoekers op.',
            'design.required' => 'Geef aan wat uw wensen zijn voor ontwerp en huisstijl.',
            'timeline.required' => 'Geef een gewenste oplevering op.',
            'name.required' => 'Naam is verplicht.',
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'Voer een geldig e-mailadres in.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('quote.builder', ['service' => $request->input('service')])
                ->withErrors($validator)
                ->withInput();
        }

        $captcha = app(\App\Services\CaptchaService::class);
        if (! $captcha->verify($request->input($captcha->tokenField()), $request->ip())) {
            return redirect()->route('quote.builder', ['service' => $request->input('service')])
                ->withErrors(['captcha' => 'Bevestig dat u geen robot bent.'])
                ->withInput();
        }

        $service = Service::where('slug', $request->input('service'))->where('is_active', true)->firstOrFail();

        if (! Auth::check()) {
            $existingUser = User::where('email', $request->input('email'))->first();

            if ($existingUser && ! $existingUser->isCustomer()) {
                return redirect()->route('quote.builder', ['service' => $request->input('service')])
                    ->withErrors(['email' => 'Dit e-mailadres is gekoppeld aan een intern account. Gebruik een ander e-mailadres.'])
                    ->withInput();
            }

            if ($existingUser && $existingUser->isCustomer()) {
                session(['url.intended' => route('quote.builder', ['service' => $request->input('service')])]);

                return redirect()->route('login')
                    ->with('success', 'U heeft al een account. Log in om verder te gaan met uw offerte-aanvraag.');
            }
        }

        $features = $request->input('features', []);
        $content = $request->input('content', []);

        $messageText = "Offerte-aanvraag via de offerte-samensteller.\n\n";
        $messageText .= "Dienst: {$service->title}\n";
        $messageText .= "Doel website: {$request->input('goal')}\n";
        $messageText .= "Aantal pagina's: {$request->input('pages')}\n";
        $messageText .= "Verwachte bezoekers per maand: {$request->input('visitors')}\n";
        $messageText .= "Ontwerp/huisstijl: {$request->input('design')}\n";
        $messageText .= "Huidige website: " . ($request->input('current_website') ?: 'Niet opgegeven') . "\n";
        $messageText .= "Gewenste functionaliteiten: " . (count($features) ? implode(', ', $features) : 'Geen') . "\n";
        $messageText .= "Content wensen: " . (count($content) ? implode(', ', $content) : 'Zelf aanleveren') . "\n";
        $messageText .= "Gewenste oplevering: {$request->input('timeline')}\n";
        $messageText .= "Budgetindicatie: " . ($request->input('budget') ?: 'Niet opgegeven') . "\n\n";
        $messageText .= "Extra informatie:\n" . ($request->input('notes') ?: '-');

        $isNewUser = false;

        [$customerService, $ticket, $user] = DB::transaction(function () use ($request, $service, $messageText, $features, $content, $ticketService, &$isNewUser) {
            $user = Auth::user();

            if (! $user) {
                $plainPassword = $request->input('password', Str::random(16));
                $user = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($plainPassword),
                    'company' => $request->input('company'),
                    'phone' => $request->input('phone'),
                    'country' => config('site.default_country', 'NL'),
                    'role' => 'customer',
                    'is_active' => true,
                    'email_verification_token' => Str::random(48),
                ]);
                $isNewUser = true;
            } else {
                $user->update(collect($request->only(['name', 'company', 'phone']))->filter()->all());
            }

            $customerService = CustomerService::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'provisioning_status' => 'not_required',
                'status' => 'suspended',
                'suspension_reason' => 'quote_request',
                'price' => 0,
                'price_type' => 'op-aanvraag',
                'billing_cycle' => 'one_time',
                'start_date' => now(),
                'auto_renew' => false,
                'payment_method' => 'payment_link',
                'notes' => 'Aangemaakt via offerte-aanvraag; prijs volgt na akkoord offerte.',
            ]);

            $ticket = $ticketService->create($user, [
                'title' => 'Offerte-aanvraag: '.$service->title,
                'description' => $messageText,
                'priority' => 'medium',
                'category' => 'offerte',
                'request_type' => 'offerte',
                'request_details' => array_filter([
                    'Dienst: '.$service->title,
                    'Doel: '.$request->input('goal'),
                    "Pagina's: ".$request->input('pages'),
                    'Bezoekers/maand: '.$request->input('visitors'),
                    'Ontwerp: '.$request->input('design'),
                    'Huidige website: '.($request->input('current_website') ?: 'Niet opgegeven'),
                    'Functionaliteiten: '.(count($features) ? implode(', ', $features) : 'Geen'),
                    'Content: '.(count($content) ? implode(', ', $content) : 'Zelf aanleveren'),
                    'Oplevering: '.$request->input('timeline'),
                    'Budget: '.($request->input('budget') ?: 'Niet opgegeven'),
                ]),
                'page' => 'offerte-samenstellen',
                'customer_notes' => $request->input('notes'),
                'customer_service_id' => $customerService->id,
            ]);

            return [$customerService, $ticket, $user];
        });

        ContactMessage::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'company' => $request->input('company'),
            'phone' => $request->input('phone'),
            'subject' => 'Offerte aanvraag: '.$service->title,
            'message' => $messageText,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_spam' => false,
        ]);

        if ($isNewUser) {
            app(\App\Services\CustomerNotificationService::class)->accountCreated($user, $plainPassword);
            app(\App\Services\CustomerNotificationService::class)->ticketCreated($user, $ticket);
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('customer.services.show', $customerService)
                ->with('success', 'Uw aanvraag is ontvangen en gekoppeld aan uw nieuwe account. Bevestig uw e-mailadres via de link die u heeft ontvangen. Ticket '.$ticket->ticket_number.' is geopend voor verdere afstemming.');
        }

        return redirect()->route('customer.services.show', $customerService)
            ->with('success', 'Uw aanvraag is ontvangen. Ticket '.$ticket->ticket_number.' is geopend zodat we samen het pakket kunnen afstemmen.');
    }
}
