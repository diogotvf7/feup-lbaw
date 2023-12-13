function editQuestion() {
  const editButton = document.getElementById('edit-question');
  const cancelEditButton = document.getElementById('cancel-edit-question');
  if (!editButton || !cancelEditButton) return;
  const submitEditButton = document.getElementById('submit-edit-question');
  const questionInput = document.getElementById('question-input');
  const end = questionInput.value.length;
  questionInput.style.height =
      (questionInput.scrollHeight > questionInput.clientHeight) ?
      (questionInput.scrollHeight) + 'px' :
      '60px';

  editButton.addEventListener('click', function() {
    editButton.classList.add('d-none');
    cancelEditButton.classList.remove('d-none');
    submitEditButton.classList.remove('d-none');
    questionInput.removeAttribute('readonly');
    questionInput.classList.remove('form-control-plaintext');
    questionInput.setSelectionRange(end, end);
    questionInput.focus();
  });

  cancelEditButton.addEventListener('click', function() {
    editButton.classList.remove('d-none');
    cancelEditButton.classList.add('d-none');
    submitEditButton.classList.add('d-none');
    questionInput.setAttribute('readonly', '');
    questionInput.classList.add('form-control-plaintext');
  });
}

export default editQuestion;
