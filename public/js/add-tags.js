const modal = document.getElementById('create-tag');
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
          <p class="m-0">${tagData.name}${
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
        modal.style.display = 'none';
        tag.value = tag.id;
        delete tag.id;
        delete tag.description;
        delete tag.search_tag_description;
        delete tag.search_tag_name;
        console.log(tag);
        tagInput.settings.whitelist.push(tag);
        tagInput.addTags([tag]);
      })
      .catch((error) => {
        displayError(error.message, document.querySelector('#name input'));
      });
}

export default function enableTagModal() {
  document.getElementById('open-modal').onclick = function() {
    modal.style.display = 'block';
  };

  document.getElementById('close-modal').onclick = function() {
    modal.style.display = 'none';
  };

  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = 'none';
    }
  };

  document.getElementById('submit-tag').onclick = function() {
    let tag_name = document.querySelector('#name input');
    let tag_description = document.querySelector('#description textarea');

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
}

function displayError(error, input) {
  const errorElement = input.parentElement.nextElementSibling;
  errorElement.classList.add('text-danger');
  errorElement.textContent = error;
}