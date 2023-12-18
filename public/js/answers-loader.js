import editAnswer from './answer-edit.js';
import handleComments from './comments-loader.js';
import enableInteractions from './interactions.js';

const answersContainer = document.getElementById('answers-container');
const answersSort = document.getElementById('answers-sort');

async function fetchAnswers() {
  const questionId = window.location.href.split('/').pop();
  const request = await fetch(
      '/api/answers?question_id=' + questionId + '&sort=' + answersSort.value);
  const response = await request.json();
  return response.answers;
}

async function loadAnswers() {
  const answers = await fetchAnswers();
  const answersHTML =
      answers.map(answer => answer + '<hr class="m-0">').join('');
  answersContainer.innerHTML = answersHTML;

  editAnswer();
  handleComments();
  enableInteractions();
}

export default loadAnswers;
