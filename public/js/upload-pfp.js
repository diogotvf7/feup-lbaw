const uploadImageModal = document.getElementById('edit-pfp');
const profilePicture = document.getElementById('profile-picture');

export default function enablePfpModal() {
    if (uploadImageModal) {
        profilePicture.addEventListener('click', function (event) {
            uploadImageModal.style.display = 'block';
            event.stopPropagation();
        });

        window.onclick = function (event) {
            if (!document.getElementsByClassName('modal-content')[0].contains(event.target) || event.target.classList.contains('close-modal'))
                closeModals();
        };
    }
}

function closeModals() {
    uploadImageModal.style.display = '';
}

