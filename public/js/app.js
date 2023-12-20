import './theme-toggler.js';
import './scroll-top.js';

import enableTagModal from './add-tags.js';
import enableUserModal from './add-user.js';
import loadAnswers from './answers-loader.js';
import enableNotifications, {markQuestionNotifRead, notificationButton} from './notifications.js';
import editQuestion from './question-edit.js';
import questionScrollObserver from './questions-fetcher.js';
import searchQuestions from './questions-search.js';
import resetFields from './reset-field.js';
import tagScrollObserver from './tags-fetcher.js';
import enablePfpModal from './upload-pfp.js';

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
  enablePfpModal();

  resetFields([
    '#editor-profile .name', '#editor-profile .username',
    '#editor-profile .email'
  ]);
}
// Question editing / Answer editing / Answer loading
else if (/^\/questions\/[0-9]+$/.test(currentPath)) {
  await editQuestion();
  await loadAnswers();

  const answersSort = document.getElementById('answers-sort');
  answersSort.addEventListener('change', loadAnswers);

  markQuestionNotifRead();
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