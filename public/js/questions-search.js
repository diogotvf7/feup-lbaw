export default async function searchQuestions(input) {
  return await fetch('/questions/search?searchTerm=' + input, {
           method: 'GET',
           headers: {
             'X-Requested-With': 'XMLHttpRequest',
           },
         })
      .then(function(response) {
        return response.text()
      })
      .then(function(html) {
        const searchDiv = document.getElementById('search-results');
        searchDiv.parentElement.innerHTML = html;
      })
      .catch(function(err) {
        console.log('Failed to fetch page: ', err);
      });
}