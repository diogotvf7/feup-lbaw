import e from"./follow-tag.js";let tagsContainer=document.getElementById("tags-container"),page=1;function noMoreTags(){let e=document.createElement("p");e.textContent="No more tags to show",e.classList.add("text-center","text-secondary","mt-3"),tagsContainer.after(e),loader.remove()}function createTagPreview(e){let t=`
    <a 
      class="d-flex flex-column justify-content-between text-decoration-none text-reset tag-preview p-3 m-1 border border-primary-subtle rounded"
      href="/questions/tag/${e.id}">
        <span class="bg-primary text-white rounded px-3 py-1 mb-3 d-flex justify-content-between gap-3 text-decoration-none">
          ${e.name}
          <button class="follow-tag btn btn-link btn-sm p-0 text-reset text-align-center border-0" data-id="${e.id}" data-status="${e.data}"> 
            ${"follows"===e.data?'<i class="bi bi-bookmark-fill"></i>':'<i class="bi bi-bookmark"></i>'}
          </button>
        </span>
        <p class="text-wrap text-break">${e.description}</p>
        <div class="d-flex justify-content-between">
          <p>${e.questions.length} questions</p>
          <p>${e.users_that_follow.length} followers</p>
        </div>
    </a>
  `;return t}async function fetchTags(){let e=new URL(window.location.href),t=await fetch("/api"+e.pathname+"?page="+page++),n=await t.json();return n.data}function insertTags(){fetchTags().then(t=>{if(0===t.length||(t.forEach(t=>{let n=createTagPreview(t);tagsContainer.insertAdjacentHTML("beforeend",n);let a=tagsContainer.lastElementChild;e(a.querySelector("button"))}),t.length<30)){noMoreTags();return}})}function handleIntersection(e){e.forEach(e=>{e.isIntersecting&&insertTags()})}function tagScrollObserver(e){let t=new IntersectionObserver(handleIntersection);t.observe(e)}export default tagScrollObserver;