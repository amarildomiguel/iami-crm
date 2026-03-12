/**
 * Angola Input Masks Plugin
 * Masks for NIF, BI, phone, currency (AOA/Kz) formatted for Angola.
 */

/**
 * Format a NIF (Número de Identificação Fiscal).
 * Accepts up to 9 digits.
 */
function formatNIF(value) {
    return value.replace(/\D/g, '').slice(0, 9);
}

/**
 * Format a BI (Bilhete de Identidade).
 * Pattern: 9 digits + 2 letters + 3 digits (e.g. 000123456LA041)
 */
function formatBI(value) {
    // Remove non-alphanumeric
    const clean = value.replace(/[^A-Z0-9]/gi, '').toUpperCase();
    // digits part (max 9)
    const digits1 = clean.replace(/[^0-9]/g, '').slice(0, 9);
    // take full alphanumeric slice up to 14 chars
    return clean.slice(0, 14);
}

/**
 * Format an Angolan phone number: +244 9XX XXX XXX
 */
function formatPhone(value) {
    // Strip everything except digits and leading +
    let digits = value.replace(/[^\d+]/g, '');

    // If starts with 244 (no +), add +
    if (digits.startsWith('244')) {
        digits = '+' + digits;
    }

    // Remove the country code if present
    let local = digits.replace(/^\+?244/, '').replace(/\D/g, '').slice(0, 9);

    if (!local) return '';

    // Format: 9XX XXX XXX
    let formatted = local;
    if (local.length > 3 && local.length <= 6) {
        formatted = local.slice(0, 3) + ' ' + local.slice(3);
    } else if (local.length > 6) {
        formatted = local.slice(0, 3) + ' ' + local.slice(3, 6) + ' ' + local.slice(6);
    }

    return '+244 ' + formatted;
}

/**
 * Format a currency value in Angolan Kwanza (Kz).
 * Input: raw number string (digits + comma)
 * Output: 1.234.567,00 Kz
 */
function formatKwanza(value) {
    // Remove everything except digits and comma
    let clean = value.replace(/[^\d,]/g, '');

    // Split on comma
    let parts = clean.split(',');
    let intPart = parts[0].replace(/\D/g, '');
    let decPart = parts.length > 1 ? parts[1].slice(0, 2) : '';

    // Add thousands separator (.)
    intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    let result = intPart;
    if (decPart !== '') {
        result += ',' + decPart;
    }

    return result ? result + ' Kz' : '';
}

/**
 * Extract raw numeric value from Kz formatted string.
 */
function parseKwanza(value) {
    return value.replace(/\./g, '').replace(' Kz', '').replace(',', '.');
}

const AngolaDirectives = {
    install(app) {
        /**
         * v-mask-nif — NIF input mask (9 digits)
         */
        app.directive('mask-nif', {
            mounted(el) {
                const input = el.tagName === 'INPUT' ? el : el.querySelector('input');
                if (!input) return;
                input.setAttribute('placeholder', '000000000');
                input.setAttribute('maxlength', '9');
                input.addEventListener('input', () => {
                    const pos = input.selectionStart;
                    input.value = formatNIF(input.value);
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                });
            },
        });

        /**
         * v-mask-bi — BI input mask (14 alphanumeric chars)
         */
        app.directive('mask-bi', {
            mounted(el) {
                const input = el.tagName === 'INPUT' ? el : el.querySelector('input');
                if (!input) return;
                input.setAttribute('placeholder', '000000000AA000');
                input.setAttribute('maxlength', '14');
                input.addEventListener('input', () => {
                    input.value = formatBI(input.value);
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                });
            },
        });

        /**
         * v-mask-phone — Angolan phone mask (+244 9XX XXX XXX)
         */
        app.directive('mask-phone', {
            mounted(el) {
                const input = el.tagName === 'INPUT' ? el : el.querySelector('input');
                if (!input) return;
                input.setAttribute('placeholder', '+244 9XX XXX XXX');
                input.addEventListener('input', () => {
                    const formatted = formatPhone(input.value);
                    input.value = formatted;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                });
            },
        });

        /**
         * v-mask-kwanza — AOA currency mask (1.234.567,00 Kz)
         */
        app.directive('mask-kwanza', {
            mounted(el) {
                const input = el.tagName === 'INPUT' ? el : el.querySelector('input');
                if (!input) return;
                input.setAttribute('placeholder', '0,00 Kz');

                input.addEventListener('focus', () => {
                    // Strip Kz suffix on focus for easier editing
                    input.value = input.value.replace(' Kz', '');
                });

                input.addEventListener('blur', () => {
                    if (input.value) {
                        input.value = formatKwanza(input.value);
                    }
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                });

                input.addEventListener('input', () => {
                    // Only allow digits and comma
                    input.value = input.value.replace(/[^\d,]/g, '');
                });
            },
        });
    },
};

export { formatNIF, formatBI, formatPhone, formatKwanza, parseKwanza };
export default AngolaDirectives;
