const upload_image_modal = document.getElementById('edit-pfp');

export default function enablePfpModal() {
    //const create_error = document.getElementById('upload-error');

    upload_image_modal.addEventListener('click', function () {
        upload_image_modal.style.display = 'block';
    });

    document.querySelectorAll('.close-modal').forEach(element => {
        element.addEventListener('click', function () {
            upload_image_modal.style.display = 'none';
        });
    });

    window.onclick = function (event) {
        closeModals();
    };
}
