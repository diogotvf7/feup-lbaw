import resetFields from './reset-field.js';

const create_user_modal = document.getElementById('create-user');
const edit_user_modal = document.getElementById('edit-user');

export default function enableUserModal() {
  const edit_error = document.getElementById('edit-error');
  const create_error = document.getElementById('create-error');

  document.getElementById('open-modal').onclick = function() {
    create_user_modal.style.display = 'block';
  };

  document.querySelectorAll('.edit-user').forEach((button) => {
    button.onclick = function() {
      const row = button.parentElement.parentElement;
      edit_user_modal.querySelector('form').action = updateAction(
          edit_user_modal.querySelector('form').action,
          row.children[0].textContent);
      edit_user_modal.querySelector('.id').value = row.children[0].textContent;
      edit_user_modal.querySelector('.name input').value =
          row.children[1].textContent;
      edit_user_modal.querySelector('.username input').value =
          row.children[2].textContent;
      edit_user_modal.querySelector('.email input').value =
          row.children[3].textContent;
      edit_user_modal.style.display = 'block';
      resetFields(
          ['#edit-user .name', '#edit-user .username', '#edit-user .email']);
    };
  });

  document.querySelectorAll('.close-modal').forEach(element => {
    element.onclick = closeModals;
  });

  window.onclick = function(event) {
    if (event.target == create_user_modal) {
      closeModals();
    } else if (event.target == edit_user_modal) {
      closeModals();
    }
  };

  if (edit_error.value != '') {
    document.querySelectorAll('.edit-user').forEach((button) => {
      if (button.parentElement.parentElement.children[0].textContent ==
          edit_error.value)
        button.click();
    });
  } else if (create_error.value != '') {
    create_user_modal.style.display = 'block';
  }
}

function closeModals() {
  if (create_user_modal) create_user_modal.style.display = 'none';
  if (edit_user_modal) edit_user_modal.style.display = 'none';
  document.querySelectorAll('.text-danger').forEach(error => {
    error.textContent = '';
  });
}

function updateAction(url, newid) {
  let updated_url = new URL(url);

  updated_url.pathname =
      updated_url.pathname.replace(/\/users\/\d+/, '/users/' + newid);

  return updated_url.toString();
}