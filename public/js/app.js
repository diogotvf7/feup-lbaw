const currentPath = window.location.pathname;
const editPage = /^\/users\/[0-9]+\/edit$/.test(currentPath);
const searchPage = /^[/\w, \/]*\/search*$/.test(currentPath);

async function request(input){
 return await fetch('/questions/search?searchTerm=' + input)
    .then(function (response) {
      // When the page is loaded convert it to text
      return response.text()
    })
    .then(function (html) {
      document.write(html);
      document.getElementById('search-bar').value = input;
      //TODO: Add autofocus on search bar
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

  searchBar.addEventListener('keyup', (e) => {
    let $input = searchBar.value;
    request($input);
  });
}




