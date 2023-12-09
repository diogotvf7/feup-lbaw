function showComments() {
  const comments = document.querySelectorAll('.show-comments');

  comments.forEach(showCommentsButton => {
    showCommentsButton.addEventListener('click', async function(e) {
      let id;
      let commentsContainer;
      if (e.target.dataset.questionId) {
        id = e.target.dataset.questionId;
        commentsContainer = document.querySelector(`#comments-container[data-question-id="${id}"]`);
      } else if (e.target.dataset.answerId) {
        id = e.target.dataset.answerId;
        commentsContainer = document.querySelector(`#comments-container[data-answer-id="${id}"]`);
      } else {
        console.error('Could not find ID for comments.');
        return;
      }

      if (!commentsContainer) {
        console.error(`Comments container for ID ${id} not found.`);
        return;
      }

      if (commentsContainer.style.display === 'none') {
        await loadComments(id, commentsContainer);
        commentsContainer.style.display = 'block';
        e.target.textContent = 'Hide Comments';
      } else {
        commentsContainer.innerHTML = '';
        commentsContainer.style.display = 'none';
        e.target.textContent = 'Show Comments';
      }
    });
  });
}

async function fetchComments(id, isQuestion) {
  let endpoint = '/api/';
  if (isQuestion) {
    endpoint += 'questions/';
  } else {
    endpoint += 'answers/';
  }
  const request = await fetch(endpoint + id + '/comments');
  const response = await request.json();
  return response.comments;
}

async function loadComments(id, commentsContainer) {
  let isQuestion = false;
  if (commentsContainer.dataset.questionId) {
    isQuestion = true;
  }
  const comments = await fetchComments(id, isQuestion);
  const commentsHTML = comments.map(comment => comment + '<hr class="m-0">').join('');
  commentsContainer.innerHTML = commentsHTML;
}

export default showComments;