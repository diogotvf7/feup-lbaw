export default function delete_user(){
    const userId = new URL(window.location.href).pathname.split('/').pop();
        return delete('/users/' + userId + '/delete',{
            method: 'DELETE',
            headers:{
            
                'X-CSRF-TOKEN':
          document.querySelector('meta[name="csrf-token"]').content
            },    
        })
        .catch(function(err){
            console.log('Failed to delete page: ', err);     
          })
}