function showComments() {
  const answers = document.querySelectorAll('.show-comments');

  answers.forEach(showCommentsButton => {
    showCommentsButton.addEventListener('click', async function(e) {
      const answerId = e.target.dataset.id;
      const commentsContainer = document.querySelector(`#comments-container[data-id="${answerId}"]`);
      const commentsDiv = document.querySelector('#comments-div');

      if (!commentsContainer) {
        console.error(`Comments container for answer ID ${answerId} not found.`);
        return;
      }

      if (commentsContainer.style.display === 'none') {
        await loadComments(answerId, commentsContainer);
        commentsContainer.style.display = 'block';
        e.target.textContent = 'Hide Comments';
        commentsDiv.classList.remove('d-none');
      } else {
        commentsContainer.innerHTML = '';
        commentsContainer.style.display = 'none';
        e.target.textContent = 'Show Comments';
        commentsDiv.classList.add('d-none');
      }
    });
  });
}

async function fetchComments(id) {
  const request = await fetch('/api/answers/' + id + '/comments');
  const response = await request.json();
  return response.comments;
}

async function loadComments(id, commentsContainer) {
  const comments = await fetchComments(id);
  const commentsHTML = comments.map(comment => comment + '<hr class="m-0">').join('');
  commentsContainer.innerHTML = commentsHTML;
}

export default showComments;
