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

export {editQuestion, stopEditingQuestion};