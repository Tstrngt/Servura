@include('errors.layout', [
    'code' => $exception->getStatusCode(),
    'title' => 'Servura is tijdelijk niet bereikbaar',
    'message' => 'Er is aan onze kant iets misgegaan. Probeer het over enkele ogenblikken opnieuw.',
])
