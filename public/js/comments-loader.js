export default function handleComments() {
  document.querySelectorAll('.show-comments')
      .forEach(button => {button.addEventListener('click', function() {
                 const commentsContainer = button.parentNode.parentNode;
                 for (let i = 4; i < commentsContainer.children.length - 2;
                      i++) {
                   commentsContainer.children[i].classList.toggle('d-none');
                 }

                 if (button.textContent == 'show more comments')
                   button.textContent = 'show less comments'
                   else button.textContent = 'show more comments'
               })});

  document.querySelectorAll('.show-comment-input')
      .forEach(button => {button.addEventListener('click', function() {
                 const options = button.parentNode;
                 const form = button.parentNode.nextSibling.nextSibling;

                 options.classList.toggle('d-none');
                 form.classList.toggle('d-none');
               })});

  document.querySelectorAll('.cancel-comment')
      .forEach(button => {button.addEventListener('click', function() {
                 const form = button.parentNode;
                 const options =
                     button.parentNode.previousSibling.previousSibling;

                 options.classList.toggle('d-none');
                 form.classList.toggle('d-none');
               })});
}
