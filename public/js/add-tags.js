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

export default function enableTagModal() {
  const edit_error = document.getElementById('edit-error');
  const create_error = document.getElementById('create-error');

  const last_created_tag = document.getElementById('last-created-tag');

  if (last_created_tag.value != '') {
    const tag =
        tagInput.whitelist.find(tag => tag.value == last_created_tag.value);
    tagInput.addTags([tag]);
  }

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
    } else if (event.target == edit_tag_modal) {
      closeModals();
    }
  };

  if (edit_error && edit_error.value != '') {
    document.querySelectorAll('.edit-tag').forEach((button) => {
      if (button.parentElement.parentElement.children[0].textContent ==
          edit_error.value)
        button.click();
    });
  } else if (create_error.value != '') {
    create_tag_modal.style.display = 'block';
  }
}

function closeModals() {
  if (create_tag_modal) create_tag_modal.style.display = 'none';
  if (edit_tag_modal) edit_tag_modal.style.display = 'none';
  document.querySelectorAll('.text-danger').forEach(error => {
    error.textContent = '';
  });
}

function updateAction(url, newid) {
  let updated_url = new URL(url);

  updated_url.pathname =
      updated_url.pathname.replace(/\/tags\/\d+/, '/tags/' + newid);

  return updated_url.toString();
}