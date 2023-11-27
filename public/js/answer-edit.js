function editAnswer() {
  const editButton = document.getElementById('edit-answer');
  const cancelEditButton = document.getElementById('cancel-edit-answer');
  const submitEditButton = document.getElementById('submit-edit-answer');
  const answerInput = document.getElementById('answer-input');
  answerInput.style.height =
      (answerInput.scrollHeight > answerInput.clientHeight) ?
      (answerInput.scrollHeight) + 'px' :
      '60px';

  editButton.addEventListener('click', function() {
    editButton.classList.add('d-none');
    cancelEditButton.classList.remove('d-none');
    submitEditButton.classList.remove('d-none');
    answerInput.removeAttribute('readonly');
    answerInput.classList.remove('form-control-plaintext');
    answerInput.focus();
  });
}

function stopEditingAnswer() {
  const editButton = document.getElementById('edit-answer');
  const cancelEditButton = document.getElementById('cancel-edit-answer');
  const submitEditButton = document.getElementById('submit-edit-answer');
  const answerInput = document.getElementById('answer-input');

  cancelEditButton.addEventListener('click', function() {
    editButton.classList.remove('d-none');
    cancelEditButton.classList.add('d-none');
    submitEditButton.classList.add('d-none');
    answerInput.setAttribute('readonly', '');
    answerInput.classList.add('form-control-plaintext');
  });
}

export {editAnswer, stopEditingAnswer};

// function editQuestion() {
//   const editButton = document.getElementById('edit-question');
//   const cancelEditButton = document.getElementById('cancel-edit-question');
//   const submitEditButton = document.getElementById('submit-edit-question');
//   const questionInput = document.getElementById('question-input');
//   questionInput.style.height =
//       (questionInput.scrollHeight > questionInput.clientHeight) ?
//       (questionInput.scrollHeight) + 'px' :
//       '60px';

//   editButton.addEventListener('click', function() {
//     editButton.classList.add('d-none');
//     cancelEditButton.classList.remove('d-none');
//     submitEditButton.classList.remove('d-none');
//     questionInput.removeAttribute('readonly');
//     questionInput.classList.remove('form-control-plaintext');
//     questionInput.focus();
//   });
// }

// function stopEditingQuestion() {
//   const editButton = document.getElementById('edit-question');
//   const cancelEditButton = document.getElementById('cancel-edit-question');
//   const submitEditButton = document.getElementById('submit-edit-question');
//   const questionInput = document.getElementById('question-input');

//   cancelEditButton.addEventListener('click', function() {
//     editButton.classList.remove('d-none');
//     cancelEditButton.classList.add('d-none');
//     submitEditButton.classList.add('d-none');
//     questionInput.setAttribute('readonly', '');
//     questionInput.classList.add('form-control-plaintext');
//   });
// }

// export {editQuestion, stopEditingQuestion};