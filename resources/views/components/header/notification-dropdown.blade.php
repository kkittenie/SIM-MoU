@php
    use App\Models\Notification;

    $unreadNotifications = Notification::unread()
        ->with('kerjaSama')
        ->latest()
        ->limit(5)
        ->get();

    $unreadCount = Notification::unread()->count();
    $hasUnread = $unreadCount > 0;
@endphp

{{-- Notification Dropdown Component --}}
<div class="relative" x-data="{
    dropdownOpen: false,
    notifying: {{ $hasUnread ? 'true' : 'false' }},
    unreadCount: {{ $unreadCount }},
    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
        if (this.dropdownOpen) {
            // Optional: you can mark them as read, but let's keep it until they click mark as read or click item
        }
    },
    closeDropdown() {
        this.dropdownOpen = false;
    },
    handleItemClick() {
        this.closeDropdown();
    },
    handleViewAllClick() {
        this.closeDropdown();
    }
}" @click.away="closeDropdown()">
    <!-- Notification Button -->
    <button
        class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-dark-900 h-11 w-11 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
        @click="toggleDropdown()"
        type="button"
    >
        <!-- Notification Badge -->
        <template x-if="notifying">
            <span
                class="absolute -top-1 -right-1 z-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white dark:ring-gray-900"
            >
                <span x-text="unreadCount"></span>
                <span
                    class="absolute inline-flex w-full h-full bg-red-400 rounded-full opacity-75 -z-1 animate-ping"
                ></span>
            </span>
        </template>

        <!-- Bell Icon -->
        <svg
            class="fill-current"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z"
                fill="currentColor"
            />
        </svg>
    </button>

    <!-- Dropdown Start -->
    <div
        x-show="dropdownOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute -right-[240px] mt-[17px] flex h-[480px] w-[350px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark sm:w-[361px] lg:right-0"
        style="display: none;"
    >
        <!-- Dropdown Header -->
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100 dark:border-gray-800">
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white/90">Notifikasi Masuk</h5>

            <button @click="closeDropdown()" class="text-gray-500 dark:text-gray-400" type="button">
                <svg
                    class="fill-current"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z"
                        fill="currentColor"
                    />
                </svg>
            </button>
        </div>

        <!-- Notification List -->
        <ul class="flex flex-col h-[320px] overflow-y-auto custom-scrollbar gap-1">
            @forelse ($unreadNotifications as $notif)
                @php
                    $isExpired = $notif->type === 'expired';
                @endphp
                <li @click="handleItemClick()">
                    <a
                        class="flex gap-3 rounded-lg border-b border-gray-100 p-3 px-4.5 py-3 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-white/5"
                        href="{{ route('notifikasi.read-and-redirect', $notif->id) }}"
                    >
                        <!-- Warning/Alert Circle Icon -->
                        <span class="flex items-center justify-center shrink-0 w-10 h-10 rounded-full text-white {{ $isExpired ? 'bg-red-500' : 'bg-amber-500' }}">
                            @if ($isExpired)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </span>

                        <span class="block text-left">
                            <span class="mb-1 block text-theme-sm font-semibold text-gray-850 dark:text-white/90 leading-tight">
                                {{ $notif->title }}
                            </span>
                            <span class="block text-[11px] text-gray-500 dark:text-gray-400 leading-tight">
                                {{ $notif->message }}
                            </span>
                            <span class="block text-[9px] text-gray-400 dark:text-gray-500 mt-1">
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </span>
                    </a>
                </li>
            @empty
                <li class="py-12 text-center text-gray-400 dark:text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="mt-2 text-xs">Semua kerja sama aktif dan aman.</p>
                </li>
            @endforelse
        </ul>

        <!-- View All Button -->
        <a
            href="{{ route('notifikasi.index') }}"
            class="mt-auto flex justify-center rounded-lg border border-gray-300 bg-white p-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
            @click="handleViewAllClick()"
        >
            Lihat Semua Notifikasi
        </a>
    </div>
    <!-- Dropdown End -->
</div>
