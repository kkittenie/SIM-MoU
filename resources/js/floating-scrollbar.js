/**
 * SIM MOU - Floating Horizontal Scrollbar Component
 * 
 * Provides a fixed floating horizontal scrollbar at the bottom of the browser
 * viewport whenever a wide table with horizontal overflow is visible on screen,
 * until the user scrolls to the bottom of the table where the native scrollbar sits.
 * 
 * Complies with:
 * - position: fixed (NOT position: sticky)
 * - IntersectionObserver + ResizeObserver + MutationObserver
 * - Two-way smooth scroll synchronization without event loop or jitter
 * - Automatic tracking of active table in viewport (multi-table support)
 * - Auto-hide when native scrollbar is visible or table leaves viewport
 * - Theme-aware styling (light / dark mode)
 */

class FloatingScrollbarManager {
    constructor() {
        this.floatingEl = null;
        this.innerEl = null;
        this.activeContainer = null;
        this.trackedContainers = new Map(); // container -> { cleanup, tableEl }
        
        this.isSyncingFromTable = false;
        this.isSyncingFromFloating = false;
        this.rafId = null;

        this.intersectionObserver = null;
        this.resizeObserver = null;
        this.mutationObserver = null;

        this.init();
    }

    init() {
        if (typeof window === 'undefined' || typeof document === 'undefined') return;

        this.createScrollbarElement();
        this.initObservers();
        this.bindGlobalEvents();

        // Initial scan for tables
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.scanContainers());
        } else {
            this.scanContainers();
        }
    }

    createScrollbarElement() {
        // Inject CSS styles for the floating scrollbar
        const styleId = 'sim-floating-scrollbar-styles';
        if (!document.getElementById(styleId)) {
            const style = document.createElement('style');
            style.id = styleId;
            style.textContent = `
                #sim-floating-scrollbar {
                    position: fixed;
                    bottom: 0;
                    z-index: 45;
                    overflow-x: auto;
                    overflow-y: hidden;
                    height: 13px;
                    box-sizing: border-box;
                    display: none;
                    background: rgba(248, 250, 252, 0.95);
                    border-top: 1px solid rgba(226, 232, 240, 0.9);
                    box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.06);
                    scrollbar-width: thin;
                    scrollbar-color: #94a3b8 rgba(241, 245, 249, 0.5);
                    pointer-events: auto;
                    user-select: none;
                }
                .dark #sim-floating-scrollbar {
                    background: rgba(16, 24, 40, 0.95);
                    border-top: 1px solid rgba(51, 65, 85, 0.7);
                    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.35);
                    scrollbar-color: #475467 rgba(30, 41, 59, 0.5);
                }
                #sim-floating-scrollbar::-webkit-scrollbar {
                    height: 10px;
                }
                #sim-floating-scrollbar::-webkit-scrollbar-track {
                    background: rgba(241, 245, 249, 0.6);
                }
                .dark #sim-floating-scrollbar::-webkit-scrollbar-track {
                    background: rgba(30, 41, 59, 0.6);
                }
                #sim-floating-scrollbar::-webkit-scrollbar-thumb {
                    background-color: #94a3b8;
                    border-radius: 9999px;
                    border: 2px solid transparent;
                    background-clip: padding-box;
                }
                #sim-floating-scrollbar::-webkit-scrollbar-thumb:hover {
                    background-color: #64748b;
                }
                .dark #sim-floating-scrollbar::-webkit-scrollbar-thumb {
                    background-color: #475467;
                }
                .dark #sim-floating-scrollbar::-webkit-scrollbar-thumb:hover {
                    background-color: #667085;
                }
            `;
            document.head.appendChild(style);
        }

        // Create container if not exists
        let scrollbar = document.getElementById('sim-floating-scrollbar');
        let inner = null;
        if (!scrollbar) {
            scrollbar = document.createElement('div');
            scrollbar.id = 'sim-floating-scrollbar';
            scrollbar.setAttribute('aria-hidden', 'true');
            
            inner = document.createElement('div');
            inner.id = 'sim-floating-scrollbar-inner';
            inner.style.height = '1px';
            inner.style.pointerEvents = 'none';

            scrollbar.appendChild(inner);
            document.body.appendChild(scrollbar);
        } else {
            inner = scrollbar.querySelector('#sim-floating-scrollbar-inner') || scrollbar.firstElementChild;
        }

        this.floatingEl = scrollbar;
        this.innerEl = inner || document.getElementById('sim-floating-scrollbar-inner');

        // Floating scrollbar -> Table container scroll synchronization
        this.floatingEl.addEventListener('scroll', () => {
            if (this.isSyncingFromTable || !this.activeContainer) return;
            this.isSyncingFromFloating = true;
            this.activeContainer.scrollLeft = this.floatingEl.scrollLeft;
            requestAnimationFrame(() => {
                this.isSyncingFromFloating = false;
            });
        }, { passive: true });
    }

    initObservers() {
        // IntersectionObserver: Trigger updates when tables enter or leave viewport
        this.intersectionObserver = new IntersectionObserver((entries) => {
            this.scheduleUpdate();
        }, {
            threshold: [0, 0.05, 0.1, 0.25, 0.5, 0.75, 1.0]
        });

        // ResizeObserver: Trigger updates when table or container changes dimensions
        this.resizeObserver = new ResizeObserver(() => {
            this.scheduleUpdate();
        });

        // MutationObserver: Auto-detect new tables or removed tables in the DOM
        this.mutationObserver = new MutationObserver(() => {
            this.scanContainers();
        });

        this.mutationObserver.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    bindGlobalEvents() {
        const onScrollOrResize = () => this.scheduleUpdate();

        window.addEventListener('scroll', onScrollOrResize, { passive: true });
        window.addEventListener('resize', onScrollOrResize, { passive: true });

        // Update when sidebar transitions complete
        window.addEventListener('transitionend', (e) => {
            if (e.target.closest && (e.target.closest('aside') || e.target.classList.contains('min-w-0'))) {
                this.scheduleUpdate();
            }
        });
    }

    scanContainers() {
        // Query common table overflow wrapper classes
        const candidateContainers = document.querySelectorAll(
            '.overflow-x-auto, [data-floating-scroll], .table-responsive, [style*="overflow-x: auto"], [style*="overflow-x:auto"]'
        );

        candidateContainers.forEach(container => {
            if (container.querySelector('table')) {
                this.registerContainer(container);
            }
        });

        // Also check any tables whose parent has overflow-x auto
        document.querySelectorAll('table').forEach(table => {
            const parent = table.parentElement;
            if (parent && !this.trackedContainers.has(parent)) {
                const style = window.getComputedStyle(parent);
                if (style.overflowX === 'auto' || style.overflowX === 'scroll' || parent.classList.contains('overflow-x-auto')) {
                    this.registerContainer(parent);
                }
            }
        });

        // Clean up detached containers
        for (const [container] of this.trackedContainers.entries()) {
            if (!document.body.contains(container)) {
                this.unregisterContainer(container);
            }
        }

        this.scheduleUpdate();
    }

    registerContainer(container) {
        if (this.trackedContainers.has(container)) return;

        const tableEl = container.querySelector('table');
        if (!tableEl) return;

        // Table container -> Floating scrollbar scroll synchronization
        const onTableScroll = () => {
            if (this.isSyncingFromFloating || container !== this.activeContainer) return;
            this.isSyncingFromTable = true;
            if (this.floatingEl) {
                this.floatingEl.scrollLeft = container.scrollLeft;
            }
            requestAnimationFrame(() => {
                this.isSyncingFromTable = false;
            });
        };

        container.addEventListener('scroll', onTableScroll, { passive: true });

        // Attach observers
        this.intersectionObserver.observe(container);
        this.resizeObserver.observe(container);
        this.resizeObserver.observe(tableEl);

        this.trackedContainers.set(container, {
            tableEl,
            cleanup: () => {
                container.removeEventListener('scroll', onTableScroll);
                this.intersectionObserver.unobserve(container);
                this.resizeObserver.unobserve(container);
                this.resizeObserver.unobserve(tableEl);
            }
        });
    }

    unregisterContainer(container) {
        const info = this.trackedContainers.get(container);
        if (info) {
            info.cleanup();
            this.trackedContainers.delete(container);
            if (this.activeContainer === container) {
                this.activeContainer = null;
                this.hide();
            }
        }
    }

    scheduleUpdate() {
        if (this.rafId) return;
        this.rafId = requestAnimationFrame(() => {
            this.rafId = null;
            this.update();
        });
    }

    update() {
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
        let bestContainer = null;
        let maxVisibleHeight = 0;

        for (const [container] of this.trackedContainers.entries()) {
            // Check if table has horizontal overflow
            const hasOverflow = container.scrollWidth > container.clientWidth + 2;
            if (!hasOverflow) continue;

            const rect = container.getBoundingClientRect();

            // Precise Visibility Conditions:
            // 1. Table top has entered the viewport (or is above it)
            // 2. Table bottom is BELOW the viewport bottom (rect.bottom > viewportHeight)
            //    -> As soon as rect.bottom <= viewportHeight, the original table scrollbar is visible!
            // 3. Table bottom is > 0 (not scrolled past off the top)
            const isFloatingNeeded = 
                rect.top < (viewportHeight - 20) &&
                rect.bottom > viewportHeight &&
                rect.width > 0;

            if (isFloatingNeeded) {
                const visibleTop = Math.max(0, rect.top);
                const visibleBottom = Math.min(viewportHeight, rect.bottom);
                const visibleHeight = visibleBottom - visibleTop;

                if (visibleHeight > maxVisibleHeight) {
                    maxVisibleHeight = visibleHeight;
                    bestContainer = container;
                }
            }
        }

        if (bestContainer) {
            this.showFor(bestContainer);
        } else {
            this.hide();
        }
    }

    showFor(container) {
        if (!this.floatingEl || !this.innerEl) return;

        const rect = container.getBoundingClientRect();
        const prevContainer = this.activeContainer;
        this.activeContainer = container;

        this.floatingEl.style.display = 'block';
        this.floatingEl.style.left = `${rect.left}px`;
        this.floatingEl.style.width = `${rect.width}px`;
        this.innerEl.style.width = `${container.scrollWidth}px`;

        // Synchronize scrollLeft on switch or update
        if (prevContainer !== container || (!this.isSyncingFromFloating && !this.isSyncingFromTable)) {
            if (this.floatingEl.scrollLeft !== container.scrollLeft) {
                this.isSyncingFromTable = true;
                this.floatingEl.scrollLeft = container.scrollLeft;
                requestAnimationFrame(() => {
                    this.isSyncingFromTable = false;
                });
            }
        }
    }

    hide() {
        if (this.floatingEl && this.floatingEl.style.display !== 'none') {
            this.floatingEl.style.display = 'none';
        }
        this.activeContainer = null;
    }
}

// Instantiate singleton
const floatingScrollbarInstance = new FloatingScrollbarManager();

// Expose globally for manual refresh if needed
if (typeof window !== 'undefined') {
    window.FloatingScrollbar = floatingScrollbarInstance;
    window.refreshFloatingScrollbars = () => floatingScrollbarInstance.scanContainers();
}

export default floatingScrollbarInstance;
