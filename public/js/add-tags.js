const tagInput = document.getElementById('tag-input');

async function fetchTags() {
  const url = new URL(window.location.href);
  const request = await fetch('/api/tags/all');
  const response = await request.json();
  response.forEach((tag) => {
    tag.value = tag.id;
    delete tag.id;
    delete tag.description;
    delete tag.approved;
    delete tag.search_tag_description;
    delete tag.search_tag_name;
  });
  return response;
}

function suggestionItemTemplate(tagData) {
  return `
      <div ${this.getAttributes(tagData)}
          class='tagify__dropdown__item ${tagData.class ? tagData.class : ''}'
          tabindex="0"
          role="option">
          <p class="m-0">${tagData.name}</p>
      </div>
  `
}

if (tagInput) {
  const tags = await fetchTags();
  console.log(tags);
  new Tagify(tagInput, {
    tagTextProp: 'name',
    whitelist: tags,
    enforceWhitelist: true,
    skipInvalid: true,
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
    },
  });
}
