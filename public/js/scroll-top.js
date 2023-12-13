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
    if (scrollContainer.scrollTo) {
      scrollContainer.scrollTo({top: 0, behavior: 'smooth'});
    } else {
      document.documentElement.scrollTo({top: 0, behavior: 'smooth'});
    }
  }
}
