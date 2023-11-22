const currentPath = window.location.pathname;
const editPage = /^\/users\/[0-9]+\/edit$/.test(currentPath);
const questionsPage = /^\/questions(?:\?.*)?$/.test(currentPath);

if (editPage) {
  /**
   * OnClick function for each input field
   * of the form. It will reset the field
   * to the default value.
   */
  function resetField(fieldName, defaultValue) {
    document.getElementById(fieldName).value = defaultValue;
  };
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

  function createQuestionPreview(question) {
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
      const tagElement = document.createElement('a');
      tagElement.href = '/questions/tag/' + tag.id;
      tagElement.classList.add(
          'badge', 'badge-primary', 'bg-primary', 'text-decoration-none');
      tagElement.textContent = tag.name;
      tags.appendChild(tagElement);
    });

    content.append(title, body, tags);

    const published = document.createElement('div');
    published.classList.add(
        'text-nowrap', 'd-flex', 'flex-column', 'justify-content-end',
        'align-content-end', 'me-5');

    const publishedInfo = document.createElement('span');
    publishedInfo.classList.add('text-secondary');

    publishedInfo.textContent =
        question.user.username + ' asked ' + question.timeAgo;

    published.appendChild(publishedInfo);

    questionPreview.append(info, content, published);

    return questionPreview;
  }

  async function fetchQuestions() {
    const urlParams = new URLSearchParams(window.location.search);
    const request = await fetch(
        '/api/questions?page=' + page++ + '&filter=' + urlParams.get('filter'));
    const response = await request.json();
    return response.data;
  }

  function insertQuestions() {
    fetchQuestions().then(questions => {
      if (questions.length === 0) {
        noMoreQuestions();
        return;
      }
      questions.forEach(question => {
        const questionPreview = createQuestionPreview(question);
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