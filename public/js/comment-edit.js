function editComment() {
    const comments = document.querySelectorAll('.comment');
  
    comments.forEach(comment => {
      const editButton = comment.querySelector('.edit-comment');
      const cancelEditButton = comment.querySelector('.cancel-edit-comment');
      const submitEditButton = comment.querySelector('.submit-edit-comment');
      const commentInput = comment.querySelector('.comment-input');
      const end = commentInput.value.length;
      commentInput.style.height =
          (commentInput.scrollHeight > commentInput.clientHeight) ?
          (commentInput.scrollHeight) + 'px' :
          '60px';
  
      if (!editButton) return;
      editButton.addEventListener('click', function() {
        editButton.classList.add('d-none');
        cancelEditButton.classList.remove('d-none');
        submitEditButton.classList.remove('d-none');
        commentInput.removeAttribute('readonly');
        commentInput.classList.remove('form-control-plaintext');
        commentInput.setSelectionRange(end, end);
        commentInput.focus();
      });
  
      if (!cancelEditButton) return;
      cancelEditButton.addEventListener('click', function() {
        editButton.classList.remove('d-none');
        cancelEditButton.classList.add('d-none');
        submitEditButton.classList.add('d-none');
        commentInput.setAttribute('readonly', '');
        commentInput.classList.add('form-control-plaintext');
      });
    });
  }
  
  export default editComment;
  