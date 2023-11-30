import sendAjaxRequest from './ajax.js';

const questionInteractions =
    document.querySelectorAll('.question-interactions');
const answerInteractions = document.querySelectorAll('.answer-interactions');

/**
 * Send a patch request to /question/upvote or /question/downvote
 * with the questionId and 'upvote' or 'downvote' on the request
 * @param {*} questionId id of the question
 * @param {*} vote if vote is 1, upvote, if -1, downvote
 * @param {*} voteCount the vote count element
 */
async function voteQuestion(vote, questionId, voteCount) {
  const response = sendAjaxRequest(
      'PATCH', `/question/${vote}/${questionId}`, null, (response) => {
        if (response.status !== 200) {
          console.log(response);

          // save response content to a variable
          const error = response.responseText;

          return;
        } else if (response.status === 200) {
          console.log(response);

          // save response content to a variable
          const success = response.responseText;
          console.log(success);
        }
        // console.log(response);
        voteCount.textContent = response.voteBalance;
      });
}

async function enableVote() {
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
        voteCount.textContent = parseInt(voteCount.textContent) - 1;
      } else {
        voteQuestion('upvote', questionId, voteCount);
        upvote.classList.remove('off');
        upvote.classList.add('on');
        downvote.classList.remove('on');
        downvote.classList.add('off');
        voteCount.textContent = parseInt(voteCount.textContent) + 1 +
            downvote.classList.contains('on');
      }
    });

    downvote.addEventListener('click', () => {
      if (downvote.classList.contains('on')) {
        voteQuestion('downvote', questionId);
        downvote.classList.remove('on');
        downvote.classList.add('off');
        voteCount.textContent = parseInt(voteCount.textContent) + 1;
      } else {
        voteQuestion('downvote', questionId);
        downvote.classList.remove('off');
        downvote.classList.add('on');
        upvote.classList.remove('on');
        upvote.classList.add('off');
        voteCount.textContent = parseInt(voteCount.textContent) - 1 -
            upvote.classList.contains('on');
      }
    });
  });
}

export default enableVote;