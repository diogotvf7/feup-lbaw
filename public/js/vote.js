/**
 * Send a patch request to /question/upvote or /question/downvote
 * with the questionId and 'upvote' or 'downvote' on the request
 * @param {*} questionId id of the question
 * @param {*} vote if vote is 1, upvote, if -1, downvote
 * @param {*} voteCount the vote count element
 */
async function voteQuestion(vote, questionId, voteCount) {
  return await fetch(`/question/${vote}/${questionId}`, {
           method: 'PATCH',
           headers: {
             'X-Requested-With': 'XMLHttpRequest',
             'X-CSRF-TOKEN':
                 document.querySelector('meta[name="csrf-token"]').content
           },
         })
      .then(function(response) {
        return response.json();
      })
      .then(function(json) {
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
      .then(function(response) {
        return response.json();
      })
      .then(function(json) {
        voteCount.textContent = json.voteBalance;
      });
}

async function enableVote(questionInteractions, answerInteractions) {
  questionInteractions.forEach((interaction) => {
    const questionId = new URL(window.location.href).pathname.split('/').pop();
    const upvote = interaction.querySelector('.upvote');
    const downvote = interaction.querySelector('.downvote');
    const voteCount = interaction.querySelector('.vote-count');

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
  });

  answerInteractions.forEach((interaction) => {
    const answerId = interaction.dataset.id;
    const upvote = interaction.querySelector('.upvote');
    const downvote = interaction.querySelector('.downvote');
    const voteCount = interaction.querySelector('.vote-count');

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

export default enableVote;