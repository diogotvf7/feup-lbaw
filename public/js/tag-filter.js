const tagInput = document.getElementById('tag-input');
let tagify = null;

async function fetchTags() {
  const request = await fetch('/api/tags/all');
  const response = await request.json();
  response.forEach((tag) => {
    tag.value = tag.id;
    delete tag.id;
    delete tag.description;
    delete tag.search_tag_description;
    delete tag.search_tag_name;
    delete tag.approved;
    delete tag.creator;
    delete tag.search;
  });
  return response;
}

function suggestionItemTemplate(tagData) {
  return `
        <div ${this.getAttributes(tagData)}
            class='tagify__dropdown__item ${tagData.class ? tagData.class : ''}'
            tabindex="0"
            role="option">
            <p class="tagify-text m-0">${tagData.name}</p>
        </div>
    `
}

function tagTemplate(tagData) {
  return `
      <tag title='${tagData.value}' contenteditable='false' spellcheck="false"
      class='tagify__tag ${
      tagData.class ?
          tagData.class :
          ''} badge bg-primary d-flex gap-2' ${this.getAttributes(tagData)}>
        <x title='remove tag' class='tagify__tag__removeBtn text-white'></x>
        <span class='tagify__tag-text'>
          ${tagData.name}
        </span>
  
      </tag>
  `
}

export default async function enableTagFilter() {
  const url = new URL(window.location.href);
  const tags = decodeURIComponent(new URLSearchParams(url.search).get('tags'));

  if (tagInput) {
    tagify = new Tagify(document.getElementById('tag-input'), {
      tagTextProp: 'name',
      whitelist: await fetchTags(),
      enforceWhitelist: true,
      skipInvalid: true,
      userInput: true,
      dropdown: {
        enabled: 0,
        closeOnSelect: false,
        searchKeys: [
          'name',
        ]
      },
      autocomplete: {
        enabled: 1,
        // rightKey: true,
      },
      templates: {
        dropdownItem: suggestionItemTemplate,
        tag: tagTemplate,
      },
    });

    if (tags.length > 0) tagify.addTags(JSON.parse(tags));
  }
};