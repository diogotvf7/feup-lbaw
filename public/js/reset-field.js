function resetFields([...fields]) {
  fields.forEach(field => {
    const formGroup = document.getElementById(field);
    const input =
        formGroup.querySelector('input') || formGroup.querySelector('textarea');
    const defaultValue = input.value;
    const button = formGroup.querySelector('button');

    button.addEventListener('click', () => {
      input.value = defaultValue;
    });
  });
}

export default resetFields;