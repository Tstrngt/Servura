<div class="border-b border-gray-200 mb-6">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <a href="{{ route('admin.financial.invoices') }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('admin.financial.invoices') ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Facturen
        </a>
        <a href="{{ route('admin.financial.transactions') }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('admin.financial.transactions') ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Transacties
        </a>
        <a href="{{ route('admin.financial.quotes') }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('admin.financial.quotes') ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Offertes
        </a>
        <a href="{{ route('admin.financial.logs') }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('admin.financial.logs') ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Logboek
        </a>
    </nav>
</div>
