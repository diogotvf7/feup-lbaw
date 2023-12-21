export default async function search(input) {
  const url = new URL(window.location.href);
  const searchParams = new URLSearchParams(url.search);

  searchParams.set('searchTerm', input);
  url.search = searchParams;

  return await fetch(url.href, {
           method: 'GET',
           headers: {
             'X-Requested-With': 'XMLHttpRequest',
           },
         })
      .then(function(response) {
        return response.text()
      })
      .then(function(html) {
        const searchDiv = document.getElementById('myTabContent');
        // console.log(searchDiv);
        searchDiv.innerHTML = html;
      })
      .catch(function(err) {
        console.log('Failed to fetch page: ', err);
      });
}