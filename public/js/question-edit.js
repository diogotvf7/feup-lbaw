function editQuestion() {
  const editButton = document.getElementById('edit-question');
  const cancelEditButton = document.getElementById('cancel-edit-question');
  if (!editButton || !cancelEditButton) return;
  const submitEditButton = document.getElementById('submit-edit-question');
  const questionInput = document.getElementById('question-input');
  const tagLabel = document.getElementById('tag-label');
  const tagInput = document.getElementById('tag-input');
  let tagifyInstance;
  let initialTagsAdded = false;

  const end = questionInput.value.length;
  questionInput.style.height =
      (questionInput.scrollHeight > questionInput.clientHeight) ?
      (questionInput.scrollHeight) + 'px' :
      '60px';

  editButton.addEventListener('click', async function() {
    editButton.classList.add('d-none');
    cancelEditButton.classList.remove('d-none');
    submitEditButton.classList.remove('d-none');
    questionInput.removeAttribute('readonly');
    questionInput.classList.remove('form-control-plaintext');
    questionInput.setSelectionRange(end, end);
    questionInput.focus();
    tagLabel.classList.remove('d-none');
    tagInput.classList.remove('d-none');
    
    if (tagInput) {
      tagifyInstance = new Tagify(document.getElementById('tag-input'), {
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
      if (!initialTagsAdded) {
        const existingTags = tagInput.getAttribute('data-question-tags').split(',');
        tagifyInstance.addTags(existingTags);
        initialTagsAdded = true;
      }
    }
  });

  cancelEditButton.addEventListener('click', function() {
    editButton.classList.remove('d-none');
    cancelEditButton.classList.add('d-none');
    submitEditButton.classList.add('d-none');
    questionInput.setAttribute('readonly', '');
    questionInput.classList.add('form-control-plaintext');
    tagLabel.classList.add('d-none');
    tagInput.classList.add('d-none');
    if (tagifyInstance) {
      tagifyInstance.destroy();
    }
  });
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

export default editQuestion;