const questionsContainer = document.getElementById('questions-container');
let page = 1;

function noMoreQuestions() {
  const text = document.createElement('p');
  text.textContent = 'No more questions to show';
  text.classList.add('text-center', 'text-secondary');
  questionsContainer.appendChild(text);
  loader.remove();
}

async function createQuestionPreview(question, authenticated) {
  console.log('/questions/preview/' + question.id);
  return await fetch('/questions/preview/' + question.id, {
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
    },
  })
    .then(function (response) {
      return response.text()
    })
    .then(function (html) {
      console.log(html);
      /*  const questionPreview =
           createQuestionPreview(question, response.authenticated);*/
      loader.insertAdjacentHTML("beforebegin", html);
      //questionsContainer.insertBefore(html, loader);
      const hr = document.createElement('hr');
      questionsContainer.insertBefore(hr, loader);
    })
    .catch(function (err) {
      console.log('Failed to fetch page: ', err);
    });
}
/* 
function createQuestionPreview(question, authenticated) {
  const questionPreview = document.createElement('article');

  questionPreview.classList.add('d-flex');

  const info = document.createElement('div');
  info.classList.add(
      'd-flex', 'flex-column', 'justify-content-center', 'align-content-end',
      'text-secondary', 'me-3', 'text-nowrap', 'text-end');

  const votes = document.createElement('span');
  votes.textContent =
      question.upvotes.length - question.downvotes.length + ' votes';

  const answers = document.createElement('span');
  answers.textContent = question.answers.length + ' answers';

  info.append(votes, answers);

  const content = document.createElement('div');
  content.classList.add('flex-grow-1');

  const title = document.createElement('a');
  title.classList.add('text-decoration-none', 'text-wrap', 'text-break');
  title.href = '/questions/' + question.id
  title.textContent = question.title;

  const body = document.createElement('p');
  body.classList.add('preview-body', 'px-3', 'text-wrap', 'text-break');
  body.textContent = question.updated_version.body;

  const tags = document.createElement('div');
  tags.classList.add('d-flex', 'gap-1');

  question.tags.forEach(tag => {
    if (!tag.approved) return;
    const tagElement = document.createElement('a');
    tagElement.href = '/questions/tag/' + tag.id;
    tagElement.classList.add('badge', 'bg-primary', 'text-decoration-none');
    tagElement.textContent = tag.name;
    tags.appendChild(tagElement);
  });

  content.append(title, body, tags);

  const published = document.createElement('div');
  published.classList.add(
      'text-nowrap', 'd-flex', 'flex-column', 'justify-content-end',
      'align-content-end', 'me-5');

  const publishedInfo = document.createElement('div');
  publishedInfo.classList.add('text-secondary');

  if (question.user == null) {
    publishedInfo.innerHTML = 'Deleted Account';
  } else if (authenticated) {
    const userLink = document.createElement('a');
    userLink.href = '/users/' + question.user.id;
    userLink.classList.add('text-decoration-none');
    userLink.textContent = question.user.username;

    publishedInfo.innerHTML = '';
    publishedInfo.appendChild(userLink);
  } else {
    publishedInfo.innerHTML = question.user.username;
  }

  publishedInfo.innerHTML += ' asked ' + question.created +
      ((question.created !== question.updated) ?
           ' (updated ' + question.updated + ')' :
           '');
  published.appendChild(publishedInfo);

  questionPreview.append(info, content, published);

  return questionPreview;
} */

async function fetchQuestions() {
  const url = new URL(window.location.href);
  url.pathname = '/api' + url.pathname
  url.searchParams.append('page', page++);
  const request = await fetch(url);
  const response = await request.json();
  return response;
}

function insertQuestions() {
  fetchQuestions().then(response => {
    const questions = response.questions.data;
    if (questions.length === 0) {
      noMoreQuestions();
      return;
    }
    questions.forEach(question => {
      // const questionPreview =
      createQuestionPreview(question, response.authenticated);
      /* questionsContainer.insertBefore(questionPreview, loader);
      const hr = document.createElement('hr');
      questionsContainer.insertBefore(hr, loader); */
    });
    if (questions.length < 10) {
      noMoreQuestions();
    }
  });
}

function handleIntersection(entries) {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      insertQuestions();
    }
  });
}

function questionScrollObserver(loader) {
  const observer = new IntersectionObserver(handleIntersection);
  observer.observe(loader);
}

export default questionScrollObserver;