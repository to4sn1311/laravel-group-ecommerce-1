export function handleImagePreview(inputId, previewImgId, placeholderId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewImgId);
    const placeholder = document.getElementById(placeholderId);

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    });
}
