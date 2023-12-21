const questionsContainer = document.getElementById('questions-container');
const loader = document.getElementById('loader');

let page = 1;
let observer = null;

function noMoreQuestions() {
  const text = document.createElement('p');
  text.textContent = 'No more questions to show';
  text.classList.add('text-center', 'text-secondary');
  questionsContainer.appendChild(text);
  loader.remove();
}

async function createQuestionPreview(question, authenticated) {
  return new Promise(async (resolve, reject) => {
    try {
      const response = await fetch('/questions/preview/' + question.id, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        },
      });

      if (!response.ok) {
        throw new Error('Failed to fetch question preview');
      }

      const html = await response.text();
      loader.insertAdjacentHTML('beforebegin', html);
      const hr = document.createElement('hr');
      questionsContainer.insertBefore(hr, loader);
      resolve();
    } catch (err) {
      console.log('Failed to fetch question preview: ', err);
      reject(err);
    }
  });
}

async function fetchQuestions() {
  const url = new URL(window.location.href);
  url.pathname = '/api' + url.pathname
  url.searchParams.append('page', page++);
  const request = await fetch(url);
  const response = await request.json();
  return response;
}

async function insertQuestions() {
  observer.unobserve(loader);
  const response = await fetchQuestions();
  const questions = response.questions.data;

  if (questions.length === 0) {
    noMoreQuestions();
    return;
  }

  const questionPromises = questions.map(
      question => createQuestionPreview(question, response.authenticated));

  await Promise.all(questionPromises);
  observer.observe(loader);


  if (questions.length < 10) {
    noMoreQuestions();
  }
}

function handleIntersection(entries) {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      insertQuestions();
    }
  });
}

function questionScrollObserver() {
  observer = new IntersectionObserver(handleIntersection);
  observer.observe(loader);
}

export default questionScrollObserver;
