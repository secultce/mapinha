import {getLocale} from "@symfony/ux-translator";
import AirDatepicker from 'air-datepicker';
import '../../styles/lib/air-datepicker-custom.css';
const datepickerLocale = await import('air-datepicker/locale/'+getLocale()+'.js');

const FORM_FILTER_SIDEBAR = document.getElementById('filter-sidebar');
const PERIOD_SELECT = document.getElementById('period');
const BTN_APPLY_PERIOD_FILTER = document.getElementById('apply-period-filter');
const BTN_CLOSE_CALENDAR = document.getElementById('close-calendar');

const datepicker = new AirDatepicker(document.getElementById('datepicker'), {
    range: true,
    multipleDates: true,
    locale: await datepickerLocale.default.default,
    onSelect: function ({ date, formattedDate }) {
        const customPeriod = document.querySelector('#period option[data-name=custom]');
        customPeriod.classList.remove('d-none');
        customPeriod.innerText = formattedDate.join(' - ');

        customPeriod.value = date.map(d => {
            return d.getFullYear() + '-'
                + (d.getMonth()+1).toString().padStart(2, '0') + '-'
                + d.getDate().toString().padStart(2, '0');
        }).join(',');
        customPeriod.selected = true;

        if (date.length === 2) {
            customPeriod.selected = true;
            BTN_CLOSE_CALENDAR.click();
        }
    },
});

PERIOD_SELECT.addEventListener('change', function () {
    datepicker.clear({silent: true});
    const customPeriod = document.querySelector('#period option[data-name=custom]');
    customPeriod.classList.add('d-none');
    customPeriod.innerText = '';
});

BTN_APPLY_PERIOD_FILTER.addEventListener('click', function () {
    const period = PERIOD_SELECT.value;
    const url = new URL(window.location.href);

    url.searchParams.set('period', period);

    window.location.href = url.toString();
});

(function () {
    const searchParams = new URLSearchParams(window.location.search);

    new FormData(FORM_FILTER_SIDEBAR).forEach(function (value, key) {
        const element = FORM_FILTER_SIDEBAR.querySelector(`[name=${key}]`);
        if (element) {
            element.value = searchParams.get(key);
        }
    });

    if ('' === PERIOD_SELECT.value) {
        const period = searchParams.get('period');
        if (period) {
            const customPeriod = document.querySelector('#period option[data-name=custom]');
            const periodInnerText = period.split(',').map(date => {
                const [year, month, day] = date.split('-');
                return `${day.padStart(2, '0')}/${month.padStart(2, '0')}/${year}`;
            });

            customPeriod.value = period;
            customPeriod.selected = true;
            customPeriod.innerText = periodInnerText.join(' - ');
        }
    }
})();
