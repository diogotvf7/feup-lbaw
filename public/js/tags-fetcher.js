import enableFollowTag from './follow-tag.js';
const tagsContainer = document.getElementById('tags-container');
let page = 1;

function noMoreTags() {
  const text = document.createElement('p');
  text.textContent = 'No more tags to show';
  text.classList.add('text-center', 'text-secondary', 'mt-3');
  tagsContainer.after(text);
  loader.remove();
}

function createTagPreview(tag) {
  const tagPreview = `
    <a 
      class="d-flex flex-column justify-content-between text-decoration-none text-reset tag-preview p-3 m-1 border border-primary-subtle rounded"
      href="/questions/tag/${tag.id}">
        <span class="bg-primary text-white rounded px-3 py-1 mb-3 d-flex justify-content-between gap-3 text-decoration-none">
          ${tag.name}
          <button class="follow-tag btn btn-link btn-sm p-0 text-reset text-align-center border-0" data-id="${
      tag.id}" data-status="${tag.data}"> 
            ${
      tag.data === 'follows' ? '<i class="bi bi-bookmark-fill"></i>' :
                               '<i class="bi bi-bookmark"></i>'}
          </button>
        </span>
        <p class="text-wrap text-break">${tag.description}</p>
        <div class="d-flex justify-content-between">
          <p>${tag.questions.length} questions</p>
          <p>${tag.users_that_follow.length} followers</p>
        </div>
    </a>
  `;
  return tagPreview;
}

async function fetchTags() {
  const url = new URL(window.location.href);
  const request = await fetch('/api' + url.pathname + '?page=' + page++);
  const response = await request.json();
  return response.data;
}

function insertTags() {
  fetchTags().then(tags => {
    if (tags.length === 0) {
      noMoreTags();
      return;
    }
    tags.forEach(tag => {
      const tagPreview = createTagPreview(tag);
      tagsContainer.insertAdjacentHTML('beforeend', tagPreview);
      const tagElement = tagsContainer.lastElementChild;
      enableFollowTag(tagElement.querySelector('button'));
    });
    if (tags.length < 30) {
      noMoreTags();
      return;
    }
  });
}

function handleIntersection(entries) {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      insertTags();
    }
  });
}

function tagScrollObserver(loader) {
  const observer = new IntersectionObserver(handleIntersection);
  observer.observe(loader);
}

export default tagScrollObserver;