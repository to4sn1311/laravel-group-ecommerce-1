export function renderPagination(pagination) {
    const container = document.getElementById('pagination');
    container.innerHTML = '';

    const { current_page, last_page, next_page_url, prev_page_url } = pagination;

    if (prev_page_url) {
        const prev = document.createElement('button');
        prev.textContent = '← Prev';
        prev.onclick = () => loadProducts(prev_page_url);
        container.appendChild(prev);
    }

    const span = document.createElement('span');
    span.textContent = `Page ${current_page} of ${last_page}`;
    span.classList.add('mx-2');
    container.appendChild(span);

    if (next_page_url) {
        const next = document.createElement('button');
        next.textContent = 'Next →';
        next.onclick = () => loadProducts(next_page_url);
        container.appendChild(next);
    }
}