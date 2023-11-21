const currentPath = window.location.pathname;
const editPage = /^\/users\/[0-9]+\/edit$/.test(currentPath);
const searchPage = /^[/\w, \/]*\/search*$/.test(currentPath);

async function request(input) {
  return await fetch('/questions/search?searchTerm=' + input, {
    method: "GET",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then(function (response) {
      // When the page is loaded convert it to text
      return response.text()
    })
    .then(function (html) {
      const searchDiv = document.getElementById('search-results');
      searchDiv.parentElement.innerHTML = html;
    })
    .catch(function (err) {
      console.log('Failed to fetch page: ', err);
    });
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
} // Search bar live search
else if (searchPage) {
  const searchBar = document.getElementById('search-bar');

  searchBar.addEventListener('input', (e) => {
    let $input = searchBar.value;
    request($input);
  });
}




