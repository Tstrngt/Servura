<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::role('customer')->get();
        $staff = User::staff()->get();

        foreach ($customers as $customer) {
            // Create 1-3 tickets per customer
            $ticketCount = rand(1, 3);
            
            for ($i = 0; $i < $ticketCount; $i++) {
                $ticket = $this->createTicket($customer, $staff);
                $this->createReplies($ticket, $customer, $staff);
            }
        }

        echo "Tickets and replies created successfully!\n";
    }

    private function createTicket($customer, $staff)
    {
        $ticketData = [
            [
                'title' => 'Website werkt niet correct op mobiel',
                'description' => 'Ik merk dat de website op mijn smartphone niet goed wordt weergegeven. De menuknoppen werken niet en sommige afbeeldingen worden niet geladen. Ik heb dit geprobeerd op zowel iPhone als Android met hetzelfde resultaat. Kunnen jullie hier naar kijken?',
                'category' => 'technical',
                'priority' => 'high',
            ],
            [
                'title' => 'Vraag over facturatie',
                'description' => 'Ik heb een vraag over de laatste factuur die ik heb ontvangen. Er staat een bedrag op dat ik niet herken. Kunnen jullie een specificatie sturen van de gefactureerde diensten?',
                'category' => 'billing',
                'priority' => 'medium',
            ],
            [
                'title' => 'Nieuwe functionaliteit gewenst',
                'description' => 'Ik zou het fijn vinden als er een klantenlogin komt waar ik mijn facturen kan bekijken en downloaden. Is dit iets wat jullie kunnen ontwikkelen?',
                'category' => 'feature_request',
                'priority' => 'low',
            ],
            [
                'title' => 'Email notifications werken niet',
                'description' => 'Ik ontvang geen email notifications meer wanneer er nieuwe reacties zijn op tickets. Dit werkte vroeger wel. Kunnen jullie dit controleren?',
                'category' => 'technical',
                'priority' => 'medium',
            ],
            [
                'title' => 'Probleem met afbeeldingen uploaden',
                'description' => 'Wanneer ik probeer afbeeldingen te uploaden in het portfolio, krijg ik een foutmelding. De bestanden zijn kleiner dan 5MB en het zijn JPG bestanden. Dit gebeurt zowel in Chrome als Firefox.',
                'category' => 'bug_report',
                'priority' => 'high',
            ],
            [
                'title' => 'Vraag over SSL certificaat',
                'description' => 'Ik zie dat het SSL certificaat binnenkort verloopt. Wordt dit automatisch vernieuwd of moet ik hier iets voor doen?',
                'category' => 'technical',
                'priority' => 'medium',
            ],
        ];

        $data = $ticketData[array_rand($ticketData)];
        
        $ticket = Ticket::create([
            'user_id' => $customer->id,
            'assigned_to' => $staff->random()->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'priority' => $data['priority'],
            'status' => $this->getRandomStatus(),
        ]);

        // Set timestamps for realistic data
        $daysAgo = rand(1, 30);
        $ticket->created_at = now()->subDays($daysAgo);
        
        if ($ticket->isResolved() || $ticket->isClosed()) {
            $ticket->resolved_at = now()->subDays(max(1, $daysAgo - rand(1, 5)));
            $ticket->closed_at = now()->subDays(max(1, $daysAgo - rand(1, 3)));
        }
        
        if ($ticket->last_reply_at) {
            $ticket->last_reply_at = now()->subDays(max(1, $daysAgo - rand(1, 7)));
        }
        
        $ticket->save();

        return $ticket;
    }

    private function createReplies($ticket, $customer, $staff)
    {
        // Skip replies for closed tickets
        if ($ticket->isClosed()) {
            return;
        }

        $replyCount = rand(0, 3);
        
        for ($i = 0; $i < $replyCount; $i++) {
            $isStaffReply = (bool) rand(0, 1);
            $user = $isStaffReply ? $staff->random() : $customer;
            
            $replyData = [
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'message' => $this->getReplyMessage($isStaffReply),
                'is_internal' => false,
            ];

            TicketReply::create($replyData);
        }

        // Update last reply timestamp
        if ($replyCount > 0) {
            $ticket->updateLastReply();
        }
    }

    private function getReplyMessage($isStaffReply)
    {
        if ($isStaffReply) {
            $staffReplies = [
                'Bedankt voor uw melding. Ik ga dit direct voor u onderzoeken en kom hier zo snel mogelijk op terug.',
                'Ik heb het probleem geïdentificeerd en werk aan een oplossing. Dit zou binnen 24 uur opgelost moeten zijn.',
                'Het probleem is inmiddels opgelost. Kunt u controleren of alles weer naar behoren werkt?',
                'Ik heb de specificatie van uw factuur per email naar u gestuurd. Laat het weten als u nog vragen heeft.',
                'Dit is een interessant idee. Ik ga dit bespreken met het team en u op de hoogte brengen van de mogelijkheden.',
                'De email notifications zijn weer actief. U zou nu weer berichten moeten ontvangen.',
                'Ik heb het upload probleem opgelost. Probeer het nog eens, het zou nu moeten werken.',
                'Het SSL certificaat wordt automatisch vernieuwd. U hoeft hier niets voor te doen.',
            ];
            
            return $staffReplies[array_rand($staffReplies)];
        } else {
            $customerReplies = [
                'Bedankt voor de snelle reactie! Ik kijk uit naar de update.',
                'Perfect, het werkt nu weer. Bedankt voor de snelle service!',
                'Ik heb de specificatie ontvangen, bedankt. Alles is in orde.',
                'Dat zou geweldig zijn, ik hoor graag van u.',
                'Ik heb het gecontroleerd en het werkt nog steeds niet. Kunt u nog eens kijken?',
                'Bedankt, ik ontvang nu weer de emails.',
                'Ja, het uploaden werkt nu prima. Dank u wel!',
                'Goed om te weten, bedankt voor de duidelijkheid.',
            ];
            
            return $customerReplies[array_rand($customerReplies)];
        }
    }

    private function getRandomStatus()
    {
        $statuses = ['open', 'in_progress', 'waiting_for_customer', 'resolved', 'closed'];
        $weights = [30, 25, 15, 20, 10]; // Weighted distribution
        
        return $this->getWeightedRandom($statuses, $weights);
    }

    private function getWeightedRandom($items, $weights)
    {
        $total = array_sum($weights);
        $random = mt_rand(1, $total);
        
        foreach ($items as $i => $item) {
            $random -= $weights[$i];
            if ($random <= 0) {
                return $item;
            }
        }
        
        return $items[0];
    }
}
