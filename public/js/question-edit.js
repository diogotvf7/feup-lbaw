const editButton = document.getElementById('edit-question');
const cancelEditButton = document.getElementById('cancel-edit-question');
const submitEditButton = document.getElementById('submit-edit-question');
const questionInput = document.getElementById('question-input');
let tagInput = document.getElementById('tag-input');

let tagsContent = [];
let bodyContent = '';

const questionId = new URL(window.location.href).pathname.split('/').pop();
let tagify = null;

async function fetchQuestionTags() {
  const request = await fetch(`/api/questions/${questionId}/tags`);
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

async function fetchTags() {
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

function tagTemplate(tagData) {
  return `
      <tag title='${tagData.value}' contenteditable='false' spellcheck="false"
      class='tagify__tag ${
      tagData.class ? tagData.class :
                      ''} badge badge-primary bg-primary d-flex gap-2' ${
      this.getAttributes(tagData)}>
        <x title='remove tag' class='tagify__tag__removeBtn text-white'></x>
        <span class='tagify__tag-text'>
          ${tagData.name}
        </span>
  
      </tag>
  `
}


async function editQuestion() {
  if (tagInput) {
    const tags = await fetchQuestionTags();

    if (tags.length === 0) {
      tagInput.classList.add('d-none');
    }
    tagify = new Tagify(document.getElementById('tag-input'), {
      tagTextProp: 'name',
      whitelist: await fetchTags(),
      enforceWhitelist: true,
      skipInvalid: true,
      userInput: true,
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
        tag: tagTemplate,
      },
    });
    tagify.addTags(tags);
    tagInput = document.querySelector('.tagify');
  }

  const end = questionInput.value.length;
  questionInput.style.height =
      (questionInput.scrollHeight > questionInput.clientHeight) ?
      (questionInput.scrollHeight) + 'px' :
      '60px';

  if (!editButton || !cancelEditButton) return;

  editButton.addEventListener('click', async function() {
    editButton.classList.add('d-none');
    cancelEditButton.classList.remove('d-none');
    submitEditButton.classList.remove('d-none');
    questionInput.removeAttribute('readonly');
    questionInput.classList.remove('form-control-plaintext');
    questionInput.setSelectionRange(end, end);
    questionInput.focus();
    tagInput.classList.remove('d-none');
    tagify.setReadonly(false);

    tagsContent = tagify.value;
    bodyContent = questionInput.value;
  });

  cancelEditButton.addEventListener('click', function() {
    editButton.classList.remove('d-none');
    cancelEditButton.classList.add('d-none');
    submitEditButton.classList.add('d-none');
    questionInput.setAttribute('readonly', '');
    questionInput.classList.add('form-control-plaintext');
    if (tagInput.value === '') tagInput.classList.add('d-none');
    tagify.setReadonly(true);

    tagify.value = tagsContent;
    questionInput.value = bodyContent;
  });
}

export default editQuestion;