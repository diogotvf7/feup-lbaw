const currentPath = window.location.pathname;
const profilePage = /^\/users\/\w+$/.test(currentPath);
const editPage = /^\/users\/[0-9]+\/edit$/.test(currentPath);
const questionsPage =
    /^\/questions(?:\/(?:top|followed))?\/?$/.test(currentPath);
const createQuestionPage = /^\/questions\/create$/.test(currentPath);
const searchPage = /^[/\w, \/]*\/search*$/.test(currentPath);

async function request(input) {
  return await fetch('/questions/search?searchTerm=' + input, {
           method: 'GET',
           headers: {
             'X-Requested-With': 'XMLHttpRequest',
           },
         })
      .then(function(response) {
        // When the page is loaded convert it to text
        return response.text()
      })
      .then(function(html) {
        const searchDiv = document.getElementById('search-results');
        searchDiv.parentElement.innerHTML = html;
      })
      .catch(function(err) {
        console.log('Failed to fetch page: ', err);
      });
}


if (profilePage) {
  const navbar = document.getElementById('navbar');
  navbar.style.borderStyle = 'none';
}

if (editPage || profilePage) {
  /**
   * OnClick function for each input field
   * of the form. It will reset the field
   * to the default value.
   */
  function resetField(fieldName, defaultValue) {
    document.getElementById(fieldName).value = defaultValue;
  };
}  // Search bar live search
else if (searchPage) {
  const searchBar = document.getElementById('search-bar');

  searchBar.addEventListener('input', (e) => {
    let $input = searchBar.value;
    request($input);
  });
}

else if (questionsPage) {
  const questionsContainer = document.getElementById('questions-container');
  const loader = document.getElementById('loader');
  let page = 1;

  function noMoreQuestions() {
    const text = document.createElement('p');
    text.textContent = 'No more questions to show';
    text.classList.add('text-center', 'text-secondary');
    questionsContainer.appendChild(text);
    loader.remove();
  }

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
    title.classList = 'text-decoration-none';
    title.href = '/questions/' + question.id
    title.textContent = question.title;

    const body = document.createElement('p');
    body.textContent = question.updated_version.body;

    const tags = document.createElement('div');
    tags.classList.add('d-flex', 'gap-1');

    question.tags.forEach(tag => {
      const tagElement = document.createElement('p');
      // const tagElement = document.createElement('a');
      // tagElement.href = '/questions/tag/' + tag.id;
      tagElement.classList.add(
          'badge', 'badge-primary', 'bg-primary', 'text-decoration-none',
          'm-0' /*remover o m-0*/);
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

    if (authenticated) {
      const userLink = document.createElement('a');
      userLink.href = '/users/' + question.user.id;
      userLink.classList.add('text-decoration-none');
      userLink.textContent = question.user.username;

      publishedInfo.innerHTML = '';
      publishedInfo.appendChild(userLink);
    } else {
      publishedInfo.innerHTML = question.user.username;
    }

    publishedInfo.innerHTML += ' asked ' + question.timeAgo;
    published.appendChild(publishedInfo);

    questionPreview.append(info, content, published);

    return questionPreview;
  }

  async function fetchQuestions() {
    const url = new URL(window.location.href);
    const request = await fetch('/api' + url.pathname + '?page=' + page++);
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
        const questionPreview =
            createQuestionPreview(question, response.authenticated);
        questionsContainer.insertBefore(questionPreview, loader);
        const hr = document.createElement('hr');
        questionsContainer.insertBefore(hr, loader);
      });
    });
  }

  function handleIntersection(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        insertQuestions();
      }
    });
  }

  function createScrollObserver() {
    const observer = new IntersectionObserver(handleIntersection);
    observer.observe(loader);
  }

  createScrollObserver();
}

if (createQuestionPage) {
}
function editAnswer() {
  var editAnswerButtons = document.querySelectorAll('.edit-answer');
  editAnswerButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      let card = this.closest('.card');
      let answerBody = card.querySelector('.answer-body');
      let editInput = card.querySelector('.edit-input');
      let submitEdit = card.querySelector('.submit-edit');
      let stopEditing = card.querySelector('.stop-editing');

      answerBody.classList.add('d-none');
      editInput.classList.remove('d-none');
      editInput.removeAttribute('readonly');
      editInput.value = answerBody.textContent;
      submitEdit.classList.remove('d-none');
      stopEditing.classList.remove('d-none');
      this.classList.add('d-none');
    });
  });
}

function stopEditingAnswer() {
  var stopEditingButtons = document.querySelectorAll('.stop-editing');
  stopEditingButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      let card = this.closest('.card');
      let answerBody = card.querySelector('.answer-body');
      let editInput = card.querySelector('.edit-input');
      let submitEdit = card.querySelector('.submit-edit');
      let editButton = card.querySelector('.edit-answer');

      answerBody.classList.remove('d-none');
      editInput.classList.add('d-none');
      submitEdit.classList.add('d-none');
      this.classList.add('d-none');
      editButton.classList.remove('d-none');
    });
  });
}

function editQuestion() {
  let editQuestionButtons = document.querySelectorAll('.edit-question');
  editQuestionButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      let card = this.closest('.card');
      let questionBody = card.querySelector('.question-body');
      let editInput = card.querySelector('.edit-input');
      let submitEdit = card.querySelector('.submit-edit');
      let stopEditing = card.querySelector('.stop-editing');

      questionBody.classList.add('d-none');
      editInput.classList.remove('d-none');
      editInput.removeAttribute('readonly');
      editInput.value = questionBody.textContent;
      submitEdit.classList.remove('d-none');
      stopEditing.classList.remove('d-none');
      this.classList.add('d-none');
    });
  });
}

function stopEditingQuestion() {
  let stopEditingButtons = document.querySelectorAll('.stop-editing');
  stopEditingButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      let card = this.closest('.card');
      let questionBody = card.querySelector('.question-body');
      let editInput = card.querySelector('.edit-input');
      let submitEdit = card.querySelector('.submit-edit');
      let editButton = card.querySelector('.edit-question');

      questionBody.classList.remove('d-none');
      editInput.classList.add('d-none');
      submitEdit.classList.add('d-none');
      this.classList.add('d-none');
      editButton.classList.remove('d-none');
    });
  });
}

document.addEventListener('DOMContentLoaded', function() {
  editAnswer();
  stopEditingAnswer();
  editQuestion();
  stopEditingQuestion();
});
