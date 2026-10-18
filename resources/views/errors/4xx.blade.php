@include('errors.layout', [
    'code' => $exception->getStatusCode(),
    'title' => 'Dit verzoek kan niet worden uitgevoerd',
    'message' => 'De gevraagde pagina is niet beschikbaar of u heeft hiervoor geen toegang. Ga terug en probeer een andere route.',
])
