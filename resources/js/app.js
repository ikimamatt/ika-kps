/**
 * IKA KPS Web Application
 * Custom High-Craft Dropdown / Select Progressive Enhancement
 */

function initCustomDropdowns() {
    // Find all select elements inside a relative wrapper or custom-select-wrapper
    const selects = document.querySelectorAll('select');

    selects.forEach((select) => {
        const wrapper = select.closest('.relative') || select.closest('.custom-select-wrapper');
        if (!wrapper) {
            return;
        }

        // Prevent double initialization
        if (wrapper.dataset.customSelectInitialized === 'true') {
            return;
        }

        // Must have trailing chevron or be a designated wrapper
        const chevron = wrapper.querySelector('.material-symbols-outlined:last-child');
        if (!chevron || !chevron.textContent.includes('expand_more')) {
            return;
        }

        wrapper.dataset.customSelectInitialized = 'true';

        // 1. Hide the native select while keeping it in the DOM for standard form submissions
        select.classList.add('sr-only');
        select.tabIndex = -1;
        select.setAttribute('aria-hidden', 'true');

        // Copy classes from the native select to style our trigger button
        const selectClasses = select.className
            .replace('sr-only', '')
            .replace('appearance-none', '')
            .trim();

        // 2. Create the custom trigger button
        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = selectClasses + ' text-left flex items-center justify-between outline-none cursor-pointer';
        trigger.setAttribute('aria-haspopup', 'listbox');
        trigger.setAttribute('aria-expanded', 'false');

        if (select.disabled) {
            trigger.disabled = true;
            trigger.classList.add('opacity-50', 'cursor-not-allowed');
        }

        const labelSpan = document.createElement('span');
        labelSpan.className = 'truncate pointer-events-none select-none';

        const updateTriggerLabel = () => {
            const selectedOption = select.options[select.selectedIndex] || select.options[0];
            labelSpan.textContent = selectedOption ? selectedOption.text : 'Pilih...';
        };
        updateTriggerLabel();
        trigger.appendChild(labelSpan);

        // Insert trigger right before select
        wrapper.insertBefore(trigger, select);

        // 3. Create the floating dropdown menu
        const menu = document.createElement('div');
        menu.className = 'custom-dropdown-menu absolute left-0 min-w-full top-[calc(100%+6px)] z-50 bg-surface-container-lowest border border-outline-variant/40 rounded-2xl shadow-xl shadow-primary/10 p-1.5 max-h-64 overflow-y-auto space-y-0.5 hidden transition-all duration-150 ease-out origin-top';
        menu.setAttribute('role', 'listbox');

        const renderOptions = () => {
            menu.innerHTML = '';
            Array.from(select.options).forEach((opt, idx) => {
                const isSelected = select.selectedIndex === idx;
                const item = document.createElement('button');
                item.type = 'button';
                item.className = `w-full flex items-center justify-between px-3.5 py-2.5 text-xs sm:text-sm font-medium rounded-xl text-left transition-all duration-150 active:scale-[0.99] cursor-pointer ${
                    isSelected
                        ? 'bg-secondary/15 text-secondary font-bold'
                        : 'text-on-surface hover:bg-surface-container-high hover:text-primary'
                }`;
                item.setAttribute('role', 'option');
                item.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                item.dataset.value = opt.value;

                const textSpan = document.createElement('span');
                textSpan.className = 'truncate';
                textSpan.textContent = opt.text;
                item.appendChild(textSpan);

                if (isSelected) {
                    const checkIcon = document.createElement('span');
                    checkIcon.className = 'material-symbols-outlined text-[16px] text-secondary shrink-0 ml-2 pointer-events-none';
                    checkIcon.textContent = 'check';
                    item.appendChild(checkIcon);
                }

                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (select.selectedIndex !== idx) {
                        select.selectedIndex = idx;
                        select.value = opt.value;
                        updateTriggerLabel();
                        renderOptions();

                        // Dispatch change and input events
                        select.dispatchEvent(new Event('change', { bubbles: true }));
                        select.dispatchEvent(new Event('input', { bubbles: true }));

                        // Support inline onchange attributes (e.g. this.form.submit())
                        if (select.getAttribute('onchange')) {
                            try {
                                new Function(select.getAttribute('onchange')).call(select);
                            } catch (err) {
                                console.error('Error executing select onchange:', err);
                            }
                        }
                    }
                    closeMenu();
                    trigger.focus();
                });

                menu.appendChild(item);
            });
        };

        renderOptions();
        wrapper.appendChild(menu);

        // 4. Open / Close controls
        const openMenu = () => {
            // Close any other open dropdowns first
            document.querySelectorAll('.custom-dropdown-menu:not(.hidden)').forEach((otherMenu) => {
                otherMenu.classList.add('hidden');
                const otherWrapper = otherMenu.closest('[data-custom-select-initialized]');
                if (otherWrapper) {
                    const otherTrigger = otherWrapper.querySelector('button[aria-expanded="true"]');
                    if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
                    const otherChevron = otherWrapper.querySelector('.material-symbols-outlined:last-child');
                    if (otherChevron) {
                        otherChevron.classList.remove('rotate-180', 'text-secondary');
                    }
                }
            });

            menu.classList.remove('hidden');
            trigger.setAttribute('aria-expanded', 'true');
            trigger.classList.add('ring-2', 'ring-secondary/20', 'border-secondary');
            chevron.classList.add('rotate-180', 'text-secondary');

            // Scroll selected item into view
            const selectedItem = menu.querySelector('[aria-selected="true"]');
            if (selectedItem) {
                selectedItem.scrollIntoView({ block: 'nearest' });
            }
        };

        const closeMenu = () => {
            menu.classList.add('hidden');
            trigger.setAttribute('aria-expanded', 'false');
            trigger.classList.remove('ring-2', 'ring-secondary/20', 'border-secondary');
            chevron.classList.remove('rotate-180', 'text-secondary');
        };

        const toggleMenu = () => {
            if (menu.classList.contains('hidden')) {
                openMenu();
            } else {
                closeMenu();
            }
        };

        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleMenu();
        });

        // Trigger keyboard handling
        trigger.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMenu();
            } else if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                if (menu.classList.contains('hidden')) {
                    openMenu();
                }
                const firstOption = menu.querySelector('[aria-selected="true"]') || menu.querySelector('button');
                if (firstOption) firstOption.focus();
            }
        });

        // Menu keyboard handling
        menu.addEventListener('keydown', (e) => {
            const items = Array.from(menu.querySelectorAll('button'));
            const activeIdx = items.indexOf(document.activeElement);

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const next = items[(activeIdx + 1) % items.length];
                if (next) next.focus();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prev = items[(activeIdx - 1 + items.length) % items.length];
                if (prev) prev.focus();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                closeMenu();
                trigger.focus();
            }
        });

        // Keep in sync if select value changed externally or form reset
        select.addEventListener('change', () => {
            updateTriggerLabel();
            renderOptions();
        });

        if (select.form) {
            select.form.addEventListener('reset', () => {
                setTimeout(() => {
                    updateTriggerLabel();
                    renderOptions();
                }, 10);
            });
        }
    });
}

// Global click listener to close dropdowns when clicking outside
document.addEventListener('click', (e) => {
    if (!e.target.closest('[data-custom-select-initialized]')) {
        document.querySelectorAll('.custom-dropdown-menu:not(.hidden)').forEach((menu) => {
            menu.classList.add('hidden');
            const wrapper = menu.closest('[data-custom-select-initialized]');
            if (wrapper) {
                const trigger = wrapper.querySelector('button[aria-expanded="true"]');
                if (trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                    trigger.classList.remove('ring-2', 'ring-secondary/20', 'border-secondary');
                }
                const chevron = wrapper.querySelector('.material-symbols-outlined:last-child');
                if (chevron) {
                    chevron.classList.remove('rotate-180', 'text-secondary');
                }
            }
        });
    }
});

// Close dropdown on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.custom-dropdown-menu:not(.hidden)').forEach((menu) => {
            menu.classList.add('hidden');
            const wrapper = menu.closest('[data-custom-select-initialized]');
            if (wrapper) {
                const trigger = wrapper.querySelector('button[aria-expanded="true"]');
                if (trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                    trigger.classList.remove('ring-2', 'ring-secondary/20', 'border-secondary');
                }
                const chevron = wrapper.querySelector('.material-symbols-outlined:last-child');
                if (chevron) {
                    chevron.classList.remove('rotate-180', 'text-secondary');
                }
            }
        });
    }
});

// Initialize on DOM load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCustomDropdowns);
} else {
    initCustomDropdowns();
}

window.initCustomDropdowns = initCustomDropdowns;

/**
 * Universal Clipboard Copy Helper
 * Works in both secure contexts (HTTPS) and local HTTP development (http://*.test)
 */
window.copyToClipboard = function (text) {
    if (navigator.clipboard && window.isSecureContext) {
        return navigator.clipboard.writeText(text);
    }
    return new Promise((resolve, reject) => {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.top = '-9999px';
        textarea.style.left = '-9999px';
        textarea.style.opacity = '0';
        textarea.setAttribute('readonly', '');
        document.body.appendChild(textarea);
        textarea.select();
        try {
            const successful = document.execCommand('copy');
            document.body.removeChild(textarea);
            if (successful) {
                resolve();
            } else {
                reject(new Error('execCommand failed'));
            }
        } catch (err) {
            document.body.removeChild(textarea);
            reject(err);
        }
    });
};
