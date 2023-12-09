import resetFields from './reset-field.js';

const create_user_modal = document.getElementById('create-user');

function validate(name, username, email, password, password_confirmation) {
  let errors = 0;

  errors += (username.value.length == 0) ?
      (displayError('Insert a username.', username), 1) :
      (displayError('', username), 0);

  errors += (!validateEmail(email.value)) ?
      (displayError('Insert a valid email.', email), 1) :
      (displayError('', email), 0);

  errors += (password.value.length < 8) ?
      (displayError('Password must be at least 8 characters long.', password),
       1) :
      (displayError('', password), 0);

  errors += (password_confirmation.value.length == 0) ?
      (displayError('Confirm your password.', password_confirmation), 1) :
      (displayError('', password_confirmation), 0);

  errors += (password.value != password_confirmation.value) ?
      (displayError('Passwords do not match.', password_confirmation), 1) :
      (displayError('', password_confirmation), 0);

  return errors == 0;
}

function create(name, username, email, is_admin, password) {
  const url = '/admin/user/store';
  const csrf = document.querySelector('meta[name="csrf-token"]').content;
  const data = {
    name: name,
    username: username,
    email: email,
    type: is_admin ? 'Admin' : 'User',
    password: password
  };
  const options = {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
    },
    body: JSON.stringify(data),
  };
  fetch(url, options)
      .then((response) => {
        if (response.ok) {
          return response.json();
        }
        throw new Error('User already exists.');
      })
      .then((user) => {
        location.href = '/admin/users?sortField=id&sortDirection=desc';
      })
      .catch((error) => {
        displayError(error.message, document.querySelector('#name input'));
      });
}

// function update(id, name, description) {
//   const url = '/admin/tags/' + id + '/update';
//   const csrf = document.querySelector('meta[name="csrf-token"]').content;
//   const data = {id: id, name: name, description: description};
//   const options = {
//     method: 'PATCH',
//     headers: {
//       'Content-Type': 'application/json',
//       'X-CSRF-TOKEN': csrf,
//     },
//     body: JSON.stringify(data),
//   };
//   fetch(url, options)
//       .then((response) => {
//         if (response.ok) {
//           return response.json();
//         }
//         // throw new Error('Tag already exists.');
//       })
//       .then((tag) => {
//         if (location.pathname == '/admin/tags')
//           location.href = '/admin/tags?sortField=id&sortDirection=desc';
//       })
//       .catch((error) => {
//         displayError(error.message, document.querySelector('#name input'));
//       });
// }

export default function enableUserModal() {
  document.getElementById('open-modal').onclick = function() {
    create_user_modal.style.display = 'block';
  };

  //   document.querySelectorAll('.edit-user').forEach((button) => {
  //     button.onclick = function() {
  //       const row = button.parentElement.parentElement;
  //       edit_tag_modal.querySelector('.id').value =
  //       row.children[0].textContent; edit_tag_modal.querySelector('.name
  //       input').value =
  //           row.children[1].textContent;
  //       edit_tag_modal.querySelector('.description textarea').value =
  //           row.children[2].textContent;
  //       edit_tag_modal.style.display = 'block';
  //       resetFields(['#edit-tag .name', '#edit-tag .description']);
  //     };
  //   });

  document.querySelectorAll('.close-modal').forEach(element => {
    element.onclick = closeModals;
  });

  window.onclick = function(event) {
    if (event.target == create_user_modal) {
      closeModals();
    }
  };

  document.getElementById('submit-user').onclick = function() {
    let name = document.querySelector('#create-user .name input');
    let username = document.querySelector('#create-user .username input');
    let email = document.querySelector('#create-user .email input');
    let password = document.querySelector('#create-user .password input');
    let password_confirmation =
        document.querySelector('#create-user .password_confirmation input');
    let is_admin = document.querySelector('#create-user .type input');

    if (!validate(name, username, email, password, password_confirmation))
      return;

    create(
        name.value, username.value, email.value, is_admin.checked,
        password.value);

    name.value = '';
    username.value = '';
    email.value = '';
    password.value = '';
    password_confirmation.value = '';
    is_admin.checked = false;
  };

  //   if (document.getElementById('update-tag') != null)
  //     document.getElementById('update-tag').onclick = function() {
  //       let tag_id = document.querySelector('#edit-tag .id');
  //       let tag_name = document.querySelector('#edit-tag .name input');
  //       let tag_description =
  //           document.querySelector('#edit-tag .description textarea');

  //       if (tag_name.value.length == 0) {
  //         displayError('The Tag name is mandatory.', tag_name);
  //         return;
  //       } else {
  //         displayError('', tag_name);
  //       }
  //       if (tag_description.value.length < 10 ||
  //           tag_description.value.length > 300) {
  //         displayError(
  //             'Tag description must be between 10 and 300 characters
  //             long.', tag_description);
  //         return;
  //       } else {
  //         displayError('', tag_description);
  //       }

  //       update(tag_id.value, tag_name.value, tag_description.value);

  //       tag_id.value = '';
  //       tag_name.value = '';
  //       tag_description.value = '';
  //     };
}

function validateEmail(email) {
  return String(email).toLowerCase().match(
      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
};

function displayError(error, input) {
  const errorElement = input.nextElementSibling;
  errorElement.classList.add('text-danger');
  errorElement.textContent = error;
}

function closeModals() {
  if (create_user_modal) create_user_modal.style.display = 'none';
  //   if (edit_tag_modal) edit_tag_modal.style.display = 'none';
}