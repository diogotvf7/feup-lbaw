const currentPath = window.location.pathname;
const editPage = /^\/users\/[0-9]+\/edit$/.test(currentPath);

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

function editAnswer() {
    var editAnswerButtons = document.querySelectorAll('.edit-answer');
    editAnswerButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var card = this.closest('.card');
            var answerBody = card.querySelector('.answer-body');
            var editInput = card.querySelector('.edit-input');
            var submitEdit = card.querySelector('.submit-edit');
            var stopEditing = card.querySelector('.stop-editing');

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
            var card = this.closest('.card');
            var answerBody = card.querySelector('.answer-body');
            var editInput = card.querySelector('.edit-input');
            var submitEdit = card.querySelector('.submit-edit');
            var editButton = card.querySelector('.edit-answer');

            answerBody.classList.remove('d-none');
            editInput.classList.add('d-none');
            submitEdit.classList.add('d-none');
            this.classList.add('d-none');
            editButton.classList.remove('d-none');
        });
    });
}

function editQuestion() {
  var editQuestionButtons = document.querySelectorAll('.edit-question');
  editQuestionButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      var card = this.closest('.card');
      var questionBody = card.querySelector('.question-body');
      var editInput = card.querySelector('.edit-input');
      var submitEdit = card.querySelector('.submit-edit');
      var stopEditing = card.querySelector('.stop-editing');

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
  var stopEditingButtons = document.querySelectorAll('.stop-editing');
  stopEditingButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      var card = this.closest('.card');
      var questionBody = card.querySelector('.question-body');
      var editInput = card.querySelector('.edit-input');
      var submitEdit = card.querySelector('.submit-edit');
      var editButton = card.querySelector('.edit-question');

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
