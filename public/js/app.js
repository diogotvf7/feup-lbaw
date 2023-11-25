import {editAnswer, stopEditingAnswer} from './answer-edit.js';
import {editQuestion, stopEditingQuestion} from './question-edit.js';
import createScrollObserver from './questions-fetcher.js';
import searchQuestions from './questions-search.js';
import resetFields from './reset-field.js';


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
else if (/^\/questions(?:\/(?:top|followed))?\/?$/.test(currentPath)) {
  const loader = document.getElementById('loader');
  createScrollObserver(loader);
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
// Question editing / Answer editing
else if (/^\/questions\/[0-9]+$/.test(currentPath)) {
  editAnswer();
  stopEditingAnswer();
  editQuestion();
  stopEditingQuestion();
}