async function followQuestion() {
  const questionId = new URL(window.location.href).pathname.split('/').pop();
  return await fetch('/questions/follow/' + questionId, {
    method: 'PATCH',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN':
        document.querySelector('meta[name="csrf-token"]').content
    },
  })
    .then(function (response) {
      return response.text();
    })
    .then(function (text) {
      const followButton = document.getElementById('follow-button');
      if (text === 'Followed') {
        followButton.classList.add('on');
        followButton.classList.remove('off');
      } else {
        followButton.classList.add('off');
        followButton.classList.remove('on');
      }
    })
    .catch(function (err) {
      console.log('Failed to fetch page: ', err);
    })
}

async function voteQuestion(vote, questionId, voteCount) {
  return await fetch(`/question/${vote}/${questionId}`, {
    method: 'PATCH',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN':
        document.querySelector('meta[name="csrf-token"]').content
    },
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (json) {
      voteCount.textContent = json.voteBalance;
    });
}

async function voteAnswer(vote, answerId, voteCount) {
  return await fetch(`/answer/${vote}/${answerId}`, {
    method: 'PATCH',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN':
        document.querySelector('meta[name="csrf-token"]').content
    },
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (json) {
      voteCount.textContent = json.voteBalance;
    });
}

async function enableQuestionInteractions(questionInteractions) {
  const questionId = new URL(window.location.href).pathname.split('/').pop();
  const upvote = questionInteractions.querySelector('.upvote');
  const downvote = questionInteractions.querySelector('.downvote');
  const voteCount = questionInteractions.querySelector('.vote-count');
  const follow = document.getElementById('follow-button');

  if (upvote)
    upvote.addEventListener('click', () => {
      if (upvote.classList.contains('on')) {
        voteQuestion('upvote', questionId, voteCount);
        upvote.classList.remove('on');
        upvote.classList.add('off');
      } else {
        voteQuestion('upvote', questionId, voteCount);
        upvote.classList.remove('off');
        upvote.classList.add('on');
        downvote.classList.remove('on');
        downvote.classList.add('off');
      }
    });

  if (downvote)
    downvote.addEventListener('click', () => {
      if (downvote.classList.contains('on')) {
        voteQuestion('downvote', questionId, voteCount);
        downvote.classList.remove('on');
        downvote.classList.add('off');
      } else {
        voteQuestion('downvote', questionId, voteCount);
        downvote.classList.remove('off');
        downvote.classList.add('on');
        upvote.classList.remove('on');
        upvote.classList.add('off');
      }
    });

  if (follow) follow.addEventListener('click', followQuestion);
}

async function enableAnswerInteractions(answerInteractions) {
  answerInteractions.forEach((interaction) => {
    const answerId = interaction.dataset.id;
    const upvote = interaction.querySelector('.upvote');
    const downvote = interaction.querySelector('.downvote');
    const voteCount = interaction.querySelector('.vote-count');

    if (upvote)
      upvote.addEventListener('click', () => {
        if (upvote.classList.contains('on')) {
          voteAnswer('upvote', answerId, voteCount);
          upvote.classList.remove('on');
          upvote.classList.add('off');
        } else {
          voteAnswer('upvote', answerId, voteCount);
          upvote.classList.remove('off');
          upvote.classList.add('on');
          downvote.classList.remove('on');
          downvote.classList.add('off');
        }
      });

    if (downvote)
      downvote.addEventListener('click', () => {
        if (downvote.classList.contains('on')) {
          voteAnswer('downvote', answerId, voteCount);
          downvote.classList.remove('on');
          downvote.classList.add('off');
        } else {
          voteAnswer('downvote', answerId, voteCount);
          downvote.classList.remove('off');
          downvote.classList.add('on');
          upvote.classList.remove('on');
          upvote.classList.add('off');
        }
      });
  });
}

async function enableInteractions() {
  const questionInteractions = document.getElementById('question-interactions');
  const answerInteractions = document.querySelectorAll('.answer-interactions');
  if (questionInteractions) { enableQuestionInteractions(questionInteractions); }
  if (answerInteractions) { enableAnswerInteractions(answerInteractions); }
}

export default enableInteractions;