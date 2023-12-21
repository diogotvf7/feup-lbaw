let tagInput=document.getElementById("tag-input"),tagify=null;async function fetchTags(){let e=await fetch("/api/tags/all"),t=await e.json();return t.forEach(e=>{e.value=e.id,delete e.id,delete e.description,delete e.search_tag_description,delete e.search_tag_name,delete e.approved,delete e.creator,delete e.search}),t}function suggestionItemTemplate(e){return`
        <div ${this.getAttributes(e)}
            class='tagify__dropdown__item ${e.class?e.class:""}'
            tabindex="0"
            role="option">
            <p class="tagify-text m-0">${e.name}</p>
        </div>
    `}function tagTemplate(e){return`
      <tag title='${e.value}' contenteditable='false' spellcheck="false"
      class='tagify__tag ${e.class?e.class:""} badge bg-primary d-flex gap-2' ${this.getAttributes(e)}>
        <x title='remove tag' class='tagify__tag__removeBtn text-white'></x>
        <span class='tagify__tag-text'>
          ${e.name}
        </span>
  
      </tag>
  `}export default async function e(){let e=new URL(window.location.href),t=decodeURIComponent(new URLSearchParams(e.search).get("tags"));tagInput&&(tagify=new Tagify(document.getElementById("tag-input"),{tagTextProp:"name",whitelist:await fetchTags(),enforceWhitelist:!0,skipInvalid:!0,userInput:!0,dropdown:{enabled:0,closeOnSelect:!1,searchKeys:["name",]},autocomplete:{enabled:1},templates:{dropdownItem:suggestionItemTemplate,tag:tagTemplate}}),t.length>0&&tagify.addTags(JSON.parse(t)))};