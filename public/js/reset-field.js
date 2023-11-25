function resetFields([...fields]) {
  fields.forEach(field => {
    const formGroup = document.getElementById(field);
    const input = formGroup.querySelector('input');
    const inputDefaultValue = input.value;
    const button = formGroup.querySelector('button');

    button.addEventListener('click', () => {
      input.value = inputDefaultValue;
    });
  });
}

export default resetFields;