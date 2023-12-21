import './theme-toggler.js';
import './scroll-top.js';

import enableTagModal from './add-tags.js';
import enableUserModal from './add-user.js';
import loadAnswers from './answers-loader.js';
import enableFollowTag from './follow-tag.js';
import enableNotifications, {markQuestionNotifRead, notificationButton} from './notifications.js';
import editQuestion from './question-edit.js';
import questionScrollObserver from './questions-fetcher.js';
import resetFields from './reset-field.js';
import search from './search.js';
import {sidebarToggle, sidebarToggler} from './sidebar-toggle.js';
import enableTagFilter from './tag-filter.js';
import tagScrollObserver from './tags-fetcher.js';
import enablePfpModal from './upload-pfp.js';

const currentPath = window.location.pathname;

// Notifications logic
enableNotifications();

if (sidebarToggler) {
  sidebarToggle();
}

// Search bar live search
if (/^[/\w, \/]*\/search*$/.test(currentPath)) {
  const searchBar = document.getElementById('search-bar');
  searchBar.addEventListener('input', (e) => {
    const input = searchBar.value;
    const searchTerm = document.getElementById('search-term');
    searchTerm.value = input;
    search(input);
  });
  enableTagFilter();
}
// Questions page infinite scroll
else if (/^\/questions(?:\/(?:top|followed|tag(?:\/[0-9]+)?)?)?\/?$/.test(
             currentPath)) {
  questionScrollObserver();

  const followTag = document.getElementById('follow-tag');
  enableFollowTag(followTag);

  enableTagFilter();
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

  const currentUserPage = currentPath.split('/').pop();
  if (userId == currentUserPage) {
    document.getElementById('profile-picture').style.cursor = 'pointer';
  }

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
