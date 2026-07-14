@php
$wrapperClass = $wrapperClass ?? 'relative';
$dropdownClass = $dropdownClass ?? 'right-0 mt-2 w-80';
$bellClass = $bellClass ?? 'text-gray-500 hover:text-gray-700';
@endphp

<script>
window.notificationBell = window.notificationBell || function () {
    return {
        open: false,
        notifications: [],
        count: 0,
        init() {
            this.fetchNotifications();
        },
        fetchNotifications() {
            fetch('{{ route('notifications.unread') }}')
                .then(r => r.json())
                .then(data => {
                    this.notifications = data;
                    this.count = data.length;
                })
                .catch(() => {
                    this.notifications = [];
                    this.count = 0;
                });
        },
        read(id, link) {
            fetch('{{ url('/notifications') }}/' + id + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            }).then(() => {
                this.notifications = this.notifications.filter(n => n.id !== id);
                this.count = Math.max(0, this.count - 1);
                if (link) window.location.href = link;
            });
        },
        readAll() {
            fetch('{{ route('notifications.read-all') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            }).then(() => {
                this.notifications = [];
                this.count = 0;
            });
        },
        formatDate(value) {
            if (!value) return '';
            const date = new Date(value);
            if (isNaN(date.getTime())) return '';
            return date.toLocaleString('nl-NL', { dateStyle: 'short', timeStyle: 'short' });
        }
    }
}
</script>

<div class="{{ $wrapperClass }}" x-data="notificationBell()">
    <button @click="open = !open" type="button" class="relative inline-flex items-center {{ $bellClass }} focus:outline-none">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <span x-show="count > 0" x-text="count" x-cloak class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white ring-2 ring-white"></span>
    </button>

    <div x-show="open" x-transition @click.outside="open = false" x-cloak class="absolute {{ $dropdownClass }} z-50 max-h-96 overflow-y-auto overflow-x-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-gray-50">
            <h3 class="text-sm font-semibold text-gray-900">Meldingen</h3>
            <button x-show="count > 0" @click="readAll()" type="button" class="text-xs text-primary-600 hover:text-primary-500">Alles gelezen</button>
        </div>

        <template x-if="notifications.length === 0">
            <div class="px-4 py-6 text-center text-sm text-gray-500">
                Geen nieuwe meldingen
            </div>
        </template>

        <template x-for="notification in notifications" :key="notification.id">
            <a href="#" @click.prevent="read(notification.id, notification.link)" class="block px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <div class="text-sm font-medium text-gray-900" x-text="notification.title"></div>
                <div class="mt-1 text-sm text-gray-500 line-clamp-2" x-text="notification.message"></div>
                <div class="mt-1 text-xs text-gray-400" x-text="formatDate(notification.created_at)"></div>
            </a>
        </template>
    </div>
</div>
