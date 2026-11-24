@props([
    'name' => 'search',
    'value' => '',
    'placeholder' => 'Cari nama perusahaan atau mitra...',
    'targetId' => 'table-container',
    'variant' => 'default', // 'default' | 'landing'
])

<div class="relative flex-1" 
    x-data="liveTableSearchComponent({
        value: @js($value ?? ''),
        targetId: @js($targetId)
    })"
>
    @if($variant === 'landing')
        <input type="text" 
            name="{{ $name }}"
            x-model="query"
            @input="onInput($event)"
            @keydown.enter.prevent="performSearch(true)"
            autocomplete="off"
            placeholder="{{ $placeholder }}"
            class="landing-input w-full pr-10" />
    @else
        <!-- Leading Icon -->
        <span class="absolute -translate-y-1/2 pointer-events-none left-4 top-1/2 z-10">
            <svg class="fill-gray-400 dark:fill-gray-500" width="18" height="18" viewBox="0 0 20 20" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z" />
            </svg>
        </span>

        <input type="text" 
            name="{{ $name }}"
            x-model="query"
            @input="onInput($event)"
            @keydown.enter.prevent="performSearch(true)"
            autocomplete="off"
            placeholder="{{ $placeholder }}"
            class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-10 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
    @endif

    <!-- Clear Button -->
    <button type="button" 
        x-show="query && query.length > 0"
        x-cloak
        @click="clear()" 
        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded-md transition-colors z-10"
        title="Hapus pencarian">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

@once
<script>
function liveTableSearchComponent(config) {
    let timeout = null;
    let abortController = null;

    return {
        query: config.value || '',
        targetId: config.targetId || 'table-container',

        init() {
            // Intercept pagination clicks inside target container for seamless updates
            document.addEventListener('click', (e) => {
                const link = e.target.closest('#' + this.targetId + ' .pagination a, #' + this.targetId + ' nav a');
                if (link && link.href) {
                    e.preventDefault();
                    this.fetchUrl(link.href);
                }
            });
        },

        onInput(e) {
            this.query = e.target.value;
            this.instantFilterRows(this.query);

            clearTimeout(timeout);
            timeout = setTimeout(() => {
                this.performSearch(false);
            }, 250);
        },

        instantFilterRows(q) {
            const container = document.getElementById(this.targetId);
            if (!container) return;
            const term = q.toLowerCase().trim();
            const rows = container.querySelectorAll('tbody tr:not(.empty-row):not(#empty-row)');
            if (rows && rows.length > 0) {
                rows.forEach(row => {
                    if (!term) {
                        row.style.display = '';
                    } else {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(term) ? '' : 'none';
                    }
                });
                return;
            }

            const cards = container.querySelectorAll('.premium-card');
            if (cards && cards.length > 0) {
                cards.forEach(card => {
                    if (!term) {
                        card.style.display = '';
                    } else {
                        const text = card.textContent.toLowerCase();
                        card.style.display = text.includes(term) ? '' : 'none';
                    }
                });
            }
        },

        async performSearch(immediate = false) {
            clearTimeout(timeout);
            const form = this.$el.closest('form');
            if (!form) return;

            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            for (const [k, v] of [...params.entries()]) {
                if (!v) params.delete(k);
            }

            const url = new URL(form.action, window.location.origin);
            url.search = params.toString();

            await this.fetchUrl(url.toString());
        },

        async fetchUrl(urlStr) {
            if (abortController) {
                abortController.abort();
            }
            abortController = new AbortController();

            try {
                const res = await fetch(urlStr, {
                    signal: abortController.signal,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (!res.ok) return;

                const html = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newContainer = doc.getElementById(this.targetId);
                const currentContainer = document.getElementById(this.targetId);

                if (newContainer && currentContainer) {
                    currentContainer.innerHTML = newContainer.innerHTML;
                    window.history.replaceState({}, '', urlStr);
                    this.rebindEvents();
                }
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('Live search error:', err);
                }
            }
        },

        clear() {
            this.query = '';
            const input = this.$el.querySelector('input');
            if (input) {
                input.value = '';
                input.focus();
            }
            this.instantFilterRows('');
            this.performSearch(true);
        },

        rebindEvents() {
            if (typeof Swal !== 'undefined') {
                document.querySelectorAll('#' + this.targetId + ' .delete-form').forEach(form => {
                    if (form._hasDeleteListener) return;
                    form._hasDeleteListener = true;
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const name = this.getAttribute('data-name') || 'data';
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: `Anda akan menghapus "${name}". Tindakan ini tidak dapat dibatalkan!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submit();
                            }
                        });
                    });
                });

                document.querySelectorAll('#' + this.targetId + ' .wa-form').forEach(form => {
                    if (form._hasWaListener) return;
                    form._hasWaListener = true;
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const name = this.getAttribute('data-name') || 'mitra';
                        Swal.fire({
                            title: 'Kirim Pengingat WhatsApp?',
                            text: `Sistem akan mengirimkan template pesan pengingat masa berlaku MoU kepada PIC ${name}.`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#16a34a',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Kirim Sekarang',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submit();
                            }
                        });
                    });
                });
            }
        }
    };
}
</script>
@endonce
