import { loadProducts } from "./productTable";

export function attachPaginationEvents() {
    $(document).on('click', '#paginationLinks a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) loadProducts(url);
    });
}
