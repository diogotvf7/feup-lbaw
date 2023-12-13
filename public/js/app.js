import { editAnswer, stopEditingAnswer } from './answer-edit.js';
import { editQuestion, stopEditingQuestion } from './question-edit.js';
import questionScrollObserver from './questions-fetcher.js';
import searchQuestions from './questions-search.js';
import resetFields from './reset-field.js';
import tagScrollObserver from './tags-fetcher.js';

const currentPath = window.location.pathname;

//Notifications logic

Pusher.logToConsole = true;

const pusher = new Pusher("37abc4ec3e719eac9ea4", {
  cluster: 'eu',
  encrypted: true,
  authEndpoint: "/broadcasting/auth",
  forceTLS: true,
});

if (userId !== '') {
  const channelName = 'private-user-' + userId;
  const channel = pusher.subscribe(channelName);
  channel.bind('notification-answer', function (data) {
    console.log(`New answer: ${data.message}`);
  });
}

//Notification button

export default async function triggerEvent() {
  return await fetch('/answers/event', {
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
    },
  })
    .then(function (response) {
      return response.text()
    })
    .then(function (response) {
      console.log('Triggered event\n');
      console.log(response);
    })
    .catch(function (err) {
      console.log('Failed to fetch page: ', err);
    });
}

const notificationButton = document.getElementById('notification-button');
notificationButton.addEventListener('click', (e) => {
  console.log('Going to trigger event\n');
  triggerEvent();
})


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
// Question editing / Answer editing
else if (/^\/questions\/[0-9]+$/.test(currentPath)) {
  editAnswer();
  stopEditingAnswer();
  editQuestion();
  stopEditingQuestion();
}
