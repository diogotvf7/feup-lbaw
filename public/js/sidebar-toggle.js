const sidebarToggler = document.getElementById('sidebar-toggle');
const sidebar = document.getElementById('sidebar');

function sidebarToggle() {
  sidebarToggler.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    if (sidebar.classList.contains('active')) {
      sidebarToggler.style.left = '250px';
      sidebarToggler.childNodes[1].classList.remove('bi-chevron-double-right');
      sidebarToggler.childNodes[1].classList.add('bi-chevron-double-left');
    } else {
      sidebarToggler.style.left = '0px';
      sidebarToggler.childNodes[1].classList.add('bi-chevron-double-right');
      sidebarToggler.childNodes[1].classList.remove('bi-chevron-double-left');
    }
  });
}

export {sidebarToggle, sidebarToggler}