import {
    trans,
    PREVIOUS,
    NEXT,
    SHOWING,
    RESULTS,
    OF,
    TO,
    TABLE_TYPE_A_KEYWORD,
    TABLE_NO_RECORDS_FOUND,
    TABLE_ERROR
} from "../translator.js";

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('table.js-grid').forEach(table => {
        const headers = Array.from(table.querySelectorAll('thead th'))
            .map(th => th.textContent.trim())
            .filter(name => name);

        const data = Array.from(table.querySelectorAll('tbody tr'))
            .map(tr =>
                Array.from(tr.querySelectorAll('td'))
                    .map(td => td.innerHTML.trim())
            );

        const wrapper = document.createElement('div');
        table.parentNode.insertBefore(wrapper, table);

        const grid = new gridjs.Grid({
            columns: headers.map(name => ({
                name,
                sort: !['foto', 'imagem', 'ações'].includes(name.toLowerCase()),
                formatter: cell => gridjs.html(cell),
            })),
            data: data,
            search: true,
            pagination: { enabled: true, limit: 10 },
            className: {
                table: 'table table-striped table-hover',
                th: 'bg-dark text-white',
                td: 'bg-light'
            },
            language: {
                search: {
                    placeholder: trans(TABLE_TYPE_A_KEYWORD),
                },
                pagination: {
                    previous: trans(PREVIOUS),
                    next: trans(NEXT),
                    showing: trans(SHOWING),
                    results: trans(RESULTS),
                    of: trans(OF),
                    to: trans(TO),
                },
                noRecordsFound: trans(TABLE_NO_RECORDS_FOUND),
                error: trans(TABLE_ERROR),
            }
        });

        grid.render(wrapper);
        table.remove();
    });
});
