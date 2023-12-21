export default function enableFollowTag(button) {
  if (button) {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const button = e.target.closest('button');
      // const button = e.target.parentNode;
      const tagId = button.dataset.id;
      const url = '/api/tags/' + tagId + '/follow';
      fetch(url, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN':
            document.querySelector('meta[name="csrf-token"]').content
        },
      }).then(response => {
        if (response.ok) {
          if (button.dataset.status === 'noAuth') {
            window.location.href = '/login';
          } else if (button.dataset.status === 'follows') {
            button.dataset.status = 'unfollows';
            button.innerHTML = '<i class="bi bi-bookmark"></i>';
          } else {
            button.dataset.status = 'follows';
            button.innerHTML = '<i class="bi bi-bookmark-fill"></i>';
          }
        }
      });
    })
  }
};