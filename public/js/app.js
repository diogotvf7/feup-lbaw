const currentPath = window.location.pathname;
const editPage = /^\/users\/[0-9]+\/edit$/.test(currentPath);

if (editPage) {
  /**
   * OnClick function for each input field
   * of the form. It will reset the field
   * to the default value.
   */
  const resetField = (fieldName, defaultValue) => {
    document.getElementById(fieldName).value = defaultValue;
  };

  /**
   * This function toggles the classes of the label
   * element based on the state of the checkbox in
   * order to change the color of the button.
   */
  function toggleCheckboxLabelClass(id, checkedClass, uncheckedClass) {
    const checkbox = document.getElementById(id);
    const label = checkbox.nextElementSibling;
    checkbox.addEventListener('change', () => {
      label.classList.toggle('btn-success', checkbox.checked);
      label.classList.toggle('btn-danger', !checkbox.checked);
    });
  }

  toggleCheckboxLabelClass('admin_check');
  toggleCheckboxLabelClass('banned_check');
}
