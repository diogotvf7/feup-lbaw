export default async function search(input) {
  return await fetch('/api/search?searchTerm=' + input, {
           method: 'GET',
           headers: {
             'X-Requested-With': 'XMLHttpRequest',
           },
         })
      .then(function(response) {
        return response.text()
      })
      .then(function(html) {
        const searchDiv = document.getElementById('search-page');
        // console.log(searchDiv);
        searchDiv.innerHTML = html;
      })
      .catch(function(err) {
        console.log('Failed to fetch page: ', err);
      });
}