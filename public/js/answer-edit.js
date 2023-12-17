let answerContent = '';

function editAnswer() {
  const answers = document.querySelectorAll('.answer');

  answers.forEach(answer => {
    const editButton = answer.querySelector('.edit-answer');
    const cancelEditButton = answer.querySelector('.cancel-edit-answer');
    const submitEditButton = answer.querySelector('.submit-edit-answer');
    const answerInput = answer.querySelector('.answer-input');
    const end = answerInput.value.length;
    answerInput.style.height =
        (answerInput.scrollHeight > answerInput.clientHeight) ?
        (answerInput.scrollHeight) + 'px' :
        '60px';

    if (!editButton) return;
    editButton.addEventListener('click', function() {
      editButton.classList.add('d-none');
      cancelEditButton.classList.remove('d-none');
      submitEditButton.classList.remove('d-none');
      answerInput.removeAttribute('readonly');
      answerInput.classList.remove('form-control-plaintext');
      answerInput.setSelectionRange(end, end);
      answerInput.focus();
      answerContent = answerInput.value;
    });

    if (!cancelEditButton) return;
    cancelEditButton.addEventListener('click', function() {
      editButton.classList.remove('d-none');
      cancelEditButton.classList.add('d-none');
      submitEditButton.classList.add('d-none');
      answerInput.setAttribute('readonly', '');
      answerInput.classList.add('form-control-plaintext');
      answerInput.value = answerContent;
    });
  });
}

export default editAnswer;
