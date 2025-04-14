export function resetForm() {
    document.getElementById('product-form').reset();
    document.getElementById('product-id').value = '';
    document.querySelectorAll('.error-text').forEach(el => el.innerText = '');
    const select = $('#categories');
    select.val([]).trigger('change'); // reset bằng Select2
    select.find('option:selected').prop('selected', false);

    const previewImg = document.getElementById('preview-img');
    const placeholder = document.getElementById('image-placeholder');
    const imageInput = document.getElementById('image');

    previewImg.src = '#';
    previewImg.classList.add('hidden');
    placeholder.classList.remove('hidden');
    imageInput.value = '';
}