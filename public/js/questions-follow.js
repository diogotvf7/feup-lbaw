export default async function follow(){
  const questionId = new URL(window.location.href).pathname.split('/').pop();
    console.log(questionId);
    return await fetch('/questions/follow/' + questionId,{
        method: 'PATCH',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN':
          document.querySelector('meta[name="csrf-token"]').content
        },
      })
      .then(function(response){
        return response.text();
      })
      .then(function(text){
        const followButton = document.getElementById("follow-button"); /*check classlist is on or off */
        if (text === "Followed"){
          followButton.classList.add("on");
          followButton.classList.remove("off");
        } else {
          followButton.classList.add("off");
          followButton.classList.remove("on");
        }
      })
      .catch(function(err){
        console.log('Failed to fetch page: ', err);     
      })
}