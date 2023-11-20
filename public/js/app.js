const currentPath = window.location.pathname;
const profilePage = /^\/user\/\w+$/.test(currentPath);
const editPage = /^\/users\/[0-9]+\/edit$/.test(currentPath);


if (profilePage) {
  const navbar = document.getElementById('navbar');
  navbar.style.borderStyle = "none";
}

if (editPage) {
  /**
   * OnClick function for each input field
   * of the form. It will reset the field
   * to the default value.
   */
  function resetField(fieldName, defaultValue) {
    document.getElementById(fieldName).value = defaultValue;
  };
}
