import resetFields from './reset-field.js';

const create_tag_modal = document.getElementById('create-tag');
const edit_tag_modal = document.getElementById('edit-tag');

let tagInput = document.getElementById('tag-input');
if (tagInput) {
  tagInput = new Tagify(document.getElementById('tag-input'), {
    tagTextProp: 'name',
    whitelist: await fetchTags(),
    enforceWhitelist: true,
    skipInvalid: true,
    dropdown: {
      enabled: 0,
      closeOnSelect: false,
      searchKeys: [
        'name',
      ]
    },
    autocomplete: {
      enabled: 1,
      // rightKey: true,
    },
    templates: {
      dropdownItem: suggestionItemTemplate,
    },
  });
}

async function fetchTags() {
  const url = new URL(window.location.href);
  const request = await fetch('/api/tags/all');
  const response = await request.json();
  response.forEach((tag) => {
    tag.value = tag.id;
    delete tag.id;
    delete tag.description;
    delete tag.search_tag_description;
    delete tag.search_tag_name;
  });
  return response;
}

function suggestionItemTemplate(tagData) {
  return `
      <div ${this.getAttributes(tagData)}
          class='tagify__dropdown__item ${tagData.class ? tagData.class : ''}'
          tabindex="0"
          role="option">
          <p class="tagify-text m-0">${tagData.name}${
      tagData.approved ? '' : ' <small>(Pending approval)</small>'}</p>
      </div>
  `
}

function create(name, description) {
  const url = '/tags/store';
  const csrf = document.querySelector('meta[name="csrf-token"]').content;
  const data = {name: name, description: description};
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
        throw new Error('Tag already exists.');
      })
      .then((tag) => {
        if (location.pathname == '/admin/tags')
          location.href = '/admin/tags?sortField=id&sortDirection=desc';
        create_tag_modal.style.display = 'none';
        tag.value = tag.id;
        delete tag.id;
        delete tag.description;
        delete tag.search_tag_description;
        delete tag.search_tag_name;
        tagInput.settings.whitelist.push(tag);
        tagInput.addTags([tag]);
      })
      .catch((error) => {
        displayError(error.message, document.querySelector('#name input'));
      });
}

function update(id, name, description) {
  const url = '/admin/tags/' + id + '/update';
  const csrf = document.querySelector('meta[name="csrf-token"]').content;
  const data = {id: id, name: name, description: description};
  const options = {
    method: 'PATCH',
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
        // throw new Error('Tag already exists.');
      })
      .then((tag) => {
        if (location.pathname == '/admin/tags')
          location.href = '/admin/tags?sortField=id&sortDirection=desc';
      })
      .catch((error) => {
        displayError(error.message, document.querySelector('#name input'));
      });
}

export default function enableTagModal() {
  document.getElementById('open-modal').onclick = function() {
    create_tag_modal.style.display = 'block';
  };

  document.querySelectorAll('.edit-tag').forEach((button) => {
    button.onclick = function() {
      const row = button.parentElement.parentElement;
      edit_tag_modal.querySelector('.id').value = row.children[0].textContent;
      edit_tag_modal.querySelector('.name input').value =
          row.children[1].textContent;
      edit_tag_modal.querySelector('.description textarea').value =
          row.children[2].textContent;
      edit_tag_modal.style.display = 'block';
      resetFields(['#edit-tag .name', '#edit-tag .description']);
    };
  });

  document.querySelectorAll('.close-modal').forEach(element => {
    element.onclick = closeModals;
  });

  window.onclick = function(event) {
    if (event.target == create_tag_modal) {
      closeModals();
    }
  };

  document.getElementById('submit-tag').onclick = function() {
    let tag_name = document.querySelector('#create-tag .name input');
    let tag_description =
        document.querySelector('#create-tag .description textarea');

    if (tag_name.value.length == 0) {
      displayError('Insert a tag name.', tag_name);
      return;
    } else {
      displayError('', tag_name);
    }
    if (tag_description.value.length < 10 ||
        tag_description.value.length > 300) {
      displayError(
          'Tag description must be between 10 and 300 characters long.',
          tag_description);
      return;
    } else {
      displayError('', tag_description);
    }

    create(tag_name.value, tag_description.value);

    tag_name.value = '';
    tag_description.value = '';
  };

  if (document.getElementById('update-tag') != null)
    document.getElementById('update-tag').onclick = function() {
      let tag_id = document.querySelector('#edit-tag .id');
      let tag_name = document.querySelector('#edit-tag .name input');
      let tag_description =
          document.querySelector('#edit-tag .description textarea');

      if (tag_name.value.length == 0) {
        displayError('The Tag name is mandatory.', tag_name.parentElement);
        return;
      } else {
        displayError('', tag_name.parentElement);
      }
      if (tag_description.value.length < 10 ||
          tag_description.value.length > 300) {
        displayError(
            'Tag description must be between 10 and 300 characters long.',
            tag_description.parentElement);
        return;
      } else {
        displayError('', tag_description.parentElement);
      }

      update(tag_id.value, tag_name.value, tag_description.value);

      tag_id.value = '';
      tag_name.value = '';
      tag_description.value = '';
    };
}

function displayError(error, input) {
  const errorElement = input.nextElementSibling;
  errorElement.classList.add('text-danger');
  errorElement.textContent = error;
}

function closeModals() {
  if (create_tag_modal) create_tag_modal.style.display = 'none';
  if (edit_tag_modal) edit_tag_modal.style.display = 'none';
}