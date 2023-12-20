const uploadImageModal = document.getElementById('edit-pfp');
const profilePicture = document.getElementById('profile-picture');
let eventModalState = false;

export default function enablePfpModal() {
    //const create_error = document.getElementById('upload-error');

    profilePicture.addEventListener('click', function (event) {
        uploadImageModal.style.display = 'block';
        event.stopPropagation();
    });

    window.onclick = function (event) {
        if (!document.getElementsByClassName('modal-content')[0].contains(event.target) || event.target.classList.contains('close-modal'))
            closeModals();
    };
}

function closeModals() {
    if (uploadImageModal) { uploadImageModal.style.display = ''; };

}

