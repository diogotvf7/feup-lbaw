const currentPath = window.location.pathname;
const editPage = /^\/users\/[0-9]+\/edit$/.test(currentPath);

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
