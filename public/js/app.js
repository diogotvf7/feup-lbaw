import './theme-toggler.js';
import './scroll-top.js';

import enableTagModal from './add-tags.js';
import enableUserModal from './add-user.js';
import loadAnswers from './answers-loader.js';
import handleComments from './comments-loader.js';
import enableNotifications, {notificationButton} from './notifications.js';
import editQuestion from './question-edit.js';
import questionScrollObserver from './questions-fetcher.js';
import follow from './questions-follow.js';
import searchQuestions from './questions-search.js';
import resetFields from './reset-field.js';
import tagScrollObserver from './tags-fetcher.js';
import enableVote from './vote.js';

const currentPath = window.location.pathname;

// Notifications logic
enableNotifications();

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
// User profile page
else if (/^\/users\/\w+$/.test(currentPath)) {
  const navbar = document.getElementById('navbar');
  navbar.style.borderStyle = 'none';

  resetFields([
    '#editor-profile .name', '#editor-profile .username',
    '#editor-profile .email'
  ]);
}
// Question editing / Answer editing / Answer loading
else if (/^\/questions\/[0-9]+$/.test(currentPath)) {
  await editQuestion();
  await loadAnswers();
  handleComments();
  const answersSort = document.getElementById('answers-sort');
  const questionFollow = document.getElementById('follow-button');
  answersSort.addEventListener('change', loadAnswers);
  questionFollow.addEventListener('click', follow);
  const questionInteractions =
      document.querySelectorAll('.question-interactions');
  const answerInteractions = document.querySelectorAll('.answer-interactions');
  enableVote(questionInteractions, answerInteractions);
}
// Create Question page
else if (/^\/questions\/create$/.test(currentPath)) {
  enableTagModal();
}
// Admin tags page
else if (/^\/admin\/tags/.test(currentPath)) {
  enableTagModal();
}
// Admin users page
else if (/^\/admin\/users/.test(currentPath)) {
  enableUserModal();
}