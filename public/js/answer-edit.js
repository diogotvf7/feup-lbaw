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

export {editAnswer, stopEditingAnswer};