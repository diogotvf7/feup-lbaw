import './theme-toggler.js';
import './scroll-top.js';

import loadAnswers from './answers-loader.js';
import editQuestion from './question-edit.js';
import questionScrollObserver from './questions-fetcher.js';
import searchQuestions from './questions-search.js';
import resetFields from './reset-field.js';
import tagScrollObserver from './tags-fetcher.js';
import enableVote from './vote.js';


const currentPath = window.location.pathname;

// Search bar live search
if (/^[/\w, \/]*\/search*$/.test(currentPath)) {
  const searchBar = document.getElementById('search-bar');
  searchBar.addEventListener('input', (e) => {
    const input = searchBar.value;
    searchQuestions(input);
  });
}
// Questions page infinite scroll
else if (/^\/questions(?:\/(?:top|followed|tag(?:\/[0-9]+)?)?)?\/?$/.test(
             currentPath)) {
  const loader = document.getElementById('loader');
  questionScrollObserver(loader);
}
// Tags page infinite scroll
else if (/^\/tags\/?$/.test(currentPath)) {
  const loader = document.getElementById('loader');
  tagScrollObserver(loader);
}
// Edit user profile page
else if (/^\/users\/[0-9]+\/edit$/.test(currentPath)) {
  const navbar = document.getElementById('navbar');
  navbar.style.borderStyle = 'none';

  resetFields(['name', 'username', 'email']);
}
// User profile page
else if (/^\/users\/\w+$/.test(currentPath)) {
  resetFields(['name', 'username', 'email']);
}
// Tag edit page
else if (/^\/tags\/[0-9]+\/edit$/.test(currentPath)) {
  resetFields(['name', 'description']);
}
// Question editing / Answer editing / Answer loading
else if (/^\/questions\/[0-9]+$/.test(currentPath)) {
  editQuestion();
  await loadAnswers();
  const answersSort = document.getElementById('answers-sort');
  answersSort.addEventListener('change', loadAnswers);
  const questionInteractions =
      document.querySelectorAll('.question-interactions');
  const answerInteractions = document.querySelectorAll('.answer-interactions');
  enableVote(questionInteractions, answerInteractions);
}
