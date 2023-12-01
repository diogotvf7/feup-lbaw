const toggle = document.getElementById('theme-toggle');

const storedTheme = localStorage.getItem('bs-theme') ||
    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                                                                 'light');
if (storedTheme)
  document.documentElement.setAttribute('data-bs-theme', storedTheme)


  toggle.onclick = function() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
    let targetTheme = 'light';

    if (currentTheme === 'light') {
      targetTheme = 'dark';
    }

    document.documentElement.setAttribute('data-bs-theme', targetTheme)
    localStorage.setItem('bs-theme', targetTheme);
  };
