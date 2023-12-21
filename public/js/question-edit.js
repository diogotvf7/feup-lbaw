let editButton=document.getElementById("edit-question"),cancelEditButton=document.getElementById("cancel-edit-question"),submitEditButton=document.getElementById("submit-edit-question"),questionInput=document.getElementById("question-input"),tagInput=document.getElementById("tag-input"),questionId=new URL(window.location.href).pathname.split("/").pop(),tagsContent=[],bodyContent="",tagify=null;async function fetchQuestionTags(){let t=await fetch(`/api/questions/${questionId}/tags`),e=await t.json();return e.forEach(t=>{t.value=t.id,delete t.id,delete t.description,delete t.search_tag_description,delete t.search_tag_name}),e}async function fetchTags(){let t=await fetch("/api/tags/all"),e=await t.json();return e.forEach(t=>{t.value=t.id,delete t.id,delete t.description,delete t.search_tag_description,delete t.search_tag_name}),e}function suggestionItemTemplate(t){return`
      <div ${this.getAttributes(t)}
          class='tagify__dropdown__item ${t.class?t.class:""}'
          tabindex="0"
          role="option">
          <p class="tagify-text m-0">${t.name}${t.approved?"":" <small>(Pending approval)</small>"}</p>
      </div>
  `}function tagTemplate(t){return`
      <tag title='${t.value}' contenteditable='false' spellcheck="false"
      class='tagify__tag ${t.class?t.class:""} badge bg-primary d-flex gap-2' ${this.getAttributes(t)}>
        <x title='remove tag' class='tagify__tag__removeBtn text-white'></x>
        <span class='tagify__tag-text'>
          ${t.name}
        </span>
  
      </tag>
  `}async function editQuestion(){if(console.log("editQuestion"),tagInput){let t=await fetchQuestionTags();(tagify=new Tagify(document.getElementById("tag-input"),{tagTextProp:"name",whitelist:await fetchTags(),enforceWhitelist:!0,skipInvalid:!0,userInput:!0,dropdown:{enabled:0,closeOnSelect:!1,searchKeys:["name",]},autocomplete:{enabled:1},templates:{dropdownItem:suggestionItemTemplate,tag:tagTemplate}})).addTags(t),tagInput=document.querySelector(".tagify"),0===t.length&&(tagInput.classList.add("d-none"),console.log(tagInput.classList))}let e=questionInput.value.length;questionInput.style.height=questionInput.scrollHeight>questionInput.clientHeight?questionInput.scrollHeight+"px":"60px",editButton&&cancelEditButton&&(editButton.addEventListener("click",async function(){editButton.classList.add("d-none"),cancelEditButton.classList.remove("d-none"),submitEditButton.classList.remove("d-none"),questionInput.removeAttribute("readonly"),questionInput.classList.remove("form-control-plaintext"),questionInput.setSelectionRange(e,e),questionInput.focus(),tagInput.classList.remove("d-none"),tagify.setReadonly(!1),tagsContent=tagify.value,bodyContent=questionInput.value}),cancelEditButton.addEventListener("click",function(){editButton.classList.remove("d-none"),cancelEditButton.classList.add("d-none"),submitEditButton.classList.add("d-none"),questionInput.setAttribute("readonly",""),questionInput.classList.add("form-control-plaintext"),""===tagInput.value&&tagInput.classList.add("d-none"),tagify.setReadonly(!0),tagify.value=tagsContent,questionInput.value=bodyContent}))}export default editQuestion;