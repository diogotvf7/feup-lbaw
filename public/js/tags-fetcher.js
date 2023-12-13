const tagsContainer = document.getElementById('tags-container');
let page = 1;

function noMoreTags() {
  const text = document.createElement('p');
  text.textContent = 'No more tags to show';
  text.classList.add('text-center', 'text-secondary');
  tagsContainer.after(text);
  // tagsContainer.appendChild(text);
  loader.remove();
}

function createTagPreview(tag) {
  const tagPreview = document.createElement('article');
  tagPreview.classList.add(
      'd-flex', 'flex-column', 'justify-content-between', 'tag-preview', 'p-3',
      'm-1', 'border', 'border-primary-subtle', 'rounded');

  const h2 = document.createElement('h2');
  h2.classList.add('badge', 'bg-primary');

  const a = document.createElement('a');
  a.href = '/questions/tag/' + tag.id;
  a.classList.add('text-reset', 'text-decoration-none');
  a.textContent = tag.name;

  h2.appendChild(a);

  const description = document.createElement('p');
  description.textContent = tag.description;

  const div = document.createElement('div');
  div.classList.add('d-flex', 'justify-content-between');

  const nQuestions = document.createElement('p');
  nQuestions.textContent = tag.questions.length + ' questions';

  const nFollowers = document.createElement('p');
  nFollowers.textContent = tag.users_that_follow.length + ' followers';

  div.append(nQuestions, nFollowers);

  tagPreview.append(h2, description, div);

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
      tagsContainer.appendChild(tagPreview);
      const hr = document.createElement('hr');
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