const scrollTopButton = document.getElementById('back-top');
const scrollContainer = document.querySelector('.scroll-container');

if (scrollTopButton && scrollContainer) {
  scrollContainer.onscroll = function(event) {
    scrollFunction();
  };

  function scrollFunction() {
    if (scrollContainer.scrollTop > 20 ||
        document.documentElement.scrollTop > 20) {
      scrollTopButton.style.display = 'block';
    } else {
      scrollTopButton.style.display = 'none';
    }
  }

  scrollTopButton.addEventListener('click', backToTop);

  function backToTop() {
    scrollContainer.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }
}