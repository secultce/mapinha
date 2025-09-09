const OrderSelect = document.getElementById('order-select');

OrderSelect.addEventListener('change', () => {
    const orderValue = OrderSelect.value;

    const url = new URL(window.location.href);
    const params = url.searchParams;

    params.set('order', orderValue);

    window.location.href = `${window.location.pathname}?${params.toString()}`;
});
