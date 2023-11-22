const currentPath = window.location.pathname;
const profilePage = /^\/users\/\w+$/.test(currentPath);
const editPage = /^\/users\/[0-9]+\/edit$/.test(currentPath);
const searchPage = /^[/\w, \/]*\/search*$/.test(currentPath);

async function request(input) {
  return await fetch('/questions/search?searchTerm=' + input, {
    method: "GET",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then(function (response) {
      // When the page is loaded convert it to text
      return response.text()
    })
    .then(function (html) {
      const searchDiv = document.getElementById('search-results');
      searchDiv.parentElement.innerHTML = html;
    })
    .catch(function (err) {
      console.log('Failed to fetch page: ', err);
    });
}


if (profilePage) {
  const navbar = document.getElementById('navbar');
  navbar.style.borderStyle = "none";
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
} // Search bar live search
else if (searchPage) {
  const searchBar = document.getElementById('search-bar');

  searchBar.addEventListener('input', (e) => {
    let $input = searchBar.value;
    request($input);
  });
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
