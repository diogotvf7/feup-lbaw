export default function resetFields([...selectors]) {
  selectors.forEach(field => {
    const formGroup = document.querySelector(field);
    const input =
        formGroup.querySelector('input') || formGroup.querySelector('textarea');
    const defaultValue = input.value;
    const button = formGroup.querySelector('button');

    button.addEventListener('click', () => {
      input.value = defaultValue;
    });
  });
}
