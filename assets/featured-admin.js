(() => {
  'use strict';
  const root = document.getElementById('dm-featured-picker');
  const select = document.getElementById('dm-featured-post');
  const status = document.getElementById('dm-featured-status');
  const searchForm = document.getElementById('dm-featured-search-form');
  const saveForm = document.getElementById('dm-featured-save-form');
  if (!root || !select || !status || !saveForm || root.dataset.enhanced) return;
  const endpoint = root.dataset.searchUrl;
  const nonce = root.dataset.searchNonce;
  if (!endpoint || !nonce) return; // Keep the server-rendered selector usable.
  const original = Array.from(select.options, option => ({ id: option.value, label: option.textContent }));
  const modes = original.filter(item => item.id === 'block' || item.id === '0');
  const recent = original.filter(item => item.id !== 'block' && item.id !== '0');
  const wrapper = document.createElement('div');
  wrapper.className = 'dm-featured-combobox';
  const input = document.createElement('input');
  input.id = 'dm-featured-combobox';
  input.type = 'text';
  input.className = 'dm-featured-input';
  input.placeholder = 'Search or choose a recent post…';
  input.autocomplete = 'off';
  input.setAttribute('role', 'combobox');
  input.setAttribute('aria-autocomplete', 'list');
  input.setAttribute('aria-haspopup', 'listbox');
  input.setAttribute('aria-expanded', 'false');
  input.setAttribute('aria-controls', 'dm-featured-options');
  input.setAttribute('aria-describedby', 'dm-featured-status dm-featured-current');
  const toggle = document.createElement('button');
  toggle.type = 'button';
  toggle.className = 'dm-featured-toggle';
  toggle.textContent = '▾';
  toggle.setAttribute('aria-label', 'Show recent posts');
  toggle.setAttribute('aria-controls', 'dm-featured-options');
  toggle.tabIndex = -1;
  const list = document.createElement('div');
  list.id = 'dm-featured-options';
  list.className = 'dm-featured-options';
  list.setAttribute('role', 'listbox');
  list.setAttribute('aria-label', 'Featured essay choices');
  list.hidden = true;
  const current = document.createElement('p');
  current.id = 'dm-featured-current';
  current.className = 'description dm-featured-selection';
  wrapper.append(input, toggle, list);
  root.append(wrapper, current);
  const label = root.querySelector('label');
  if (label) label.htmlFor = input.id;
  select.hidden = true;
  if (searchForm) searchForm.hidden = true;
  root.dataset.enhanced = 'true';
  let selected = select.value;
  let selectedLabel = select.selectedOptions[0] ? select.selectedOptions[0].textContent : '';
  let items = [];
  let active = -1;
  let query = '';
  let timer;
  let controller;
  let revision = 0;
  const normalise = text => text.normalize('NFKD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase();
  const showSelected = () => { input.value = selectedLabel; current.textContent = `Selected: ${selectedLabel}`; };
  const close = () => {
    ++revision;
    clearTimeout(timer);
    if (controller) controller.abort();
    list.removeAttribute('aria-busy');
    list.hidden = true;
    input.setAttribute('aria-expanded', 'false');
    input.removeAttribute('aria-activedescendant');
    active = -1;
  };
  const highlight = index => {
    active = index;
    Array.from(list.querySelectorAll('[role="option"]')).forEach((option, i) => option.classList.toggle('is-active', i === active));
    if (active >= 0) {
      const option = list.querySelectorAll('[role="option"]')[active];
      input.setAttribute('aria-activedescendant', option.id);
      option.scrollIntoView({ block: 'nearest' });
    } else input.removeAttribute('aria-activedescendant');
  };
  const choose = item => {
    if (!Array.from(select.options).some(option => option.value === String(item.id))) select.add(new Option(item.label, String(item.id)));
    selected = String(item.id);
    selectedLabel = item.label;
    select.value = selected;
    select.dispatchEvent(new Event('change', { bubbles: true }));
    query = '';
    ++revision;
    clearTimeout(timer);
    if (controller) controller.abort();
    list.removeAttribute('aria-busy');
    showSelected();
    close();
    status.textContent = 'Essay selected. Click Save featured essay to apply it.';
  };
  const render = (posts, message) => {
    items = [...posts.map(item => ({ id: String(item.id), label: item.label })), ...modes];
    list.replaceChildren();
    if (!posts.length) {
      const empty = document.createElement('div');
      empty.className = 'dm-featured-empty';
      empty.textContent = query ? 'No matching posts. Try another title.' : 'No eligible published writing posts found.';
      list.append(empty);
    }
    items.forEach((item, index) => {
      const option = document.createElement('div');
      option.id = `dm-featured-option-${index}`;
      option.className = 'dm-featured-option';
      option.setAttribute('role', 'option');
      option.setAttribute('aria-selected', String(item.id === selected));
      option.textContent = item.label;
      option.addEventListener('pointerdown', event => event.preventDefault());
      option.addEventListener('click', () => choose(item));
      list.append(option);
    });
    highlight(-1);
    if (document.activeElement === input) { list.hidden = false; input.setAttribute('aria-expanded', 'true'); }
    status.textContent = message;
  };
  const localMatches = () => recent.filter(item => normalise(item.label).includes(normalise(query)));
  const open = () => {
    query = '';
    render(recent, 'Recent posts. Type a title to search, or choose a post below.');
    list.hidden = false;
    input.setAttribute('aria-expanded', 'true');
  };
  const search = async (term, request) => {
    controller = new AbortController();
    const url = new URL(endpoint, window.location.href);
    url.searchParams.set('action', 'dm_search_featured');
    url.searchParams.set('nonce', nonce);
    url.searchParams.set('search', term);
    list.setAttribute('aria-busy', 'true');
    try {
      const response = await fetch(url, { credentials: 'same-origin', signal: controller.signal });
      const result = await response.json();
      if (!response.ok || !result.success || !Array.isArray(result.data)) throw new Error('Search failed');
      if (request !== revision) return;
      render(result.data, result.data.length
        ? `${result.data.length} ${term ? 'matching' : 'recent'} posts${result.data.length === 20 ? ' (up to 20; refine your search if needed)' : ''}. Choose a result.`
        : 'No matching published writing posts. Try another title.');
    } catch (error) {
      if (error.name !== 'AbortError' && request === revision) render(localMatches(), 'Could not load more posts. Available matches are shown; try again or reload. Your selection is unchanged.');
    } finally {
      if (request === revision) list.removeAttribute('aria-busy');
    }
  };
  input.addEventListener('focus', () => { open(); input.select(); });
  input.addEventListener('click', () => { if (list.hidden) open(); });
  input.addEventListener('input', () => {
    query = input.value.trim();
    clearTimeout(timer);
    const request = ++revision;
    if (controller) controller.abort();
    render(localMatches(), query ? 'Searching post titles…' : 'Recent posts. Choose an essay.');
    timer = setTimeout(() => search(query, request), 250);
  });
  input.addEventListener('keydown', event => {
    if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
      event.preventDefault();
      if (list.hidden) open();
      const direction = event.key === 'ArrowDown' ? 1 : -1;
      highlight(active < 0 ? (direction === 1 ? 0 : items.length - 1) : (active + direction + items.length) % items.length);
    } else if (event.key === 'Enter' && !list.hidden) {
      event.preventDefault();
      if (active >= 0) choose(items[active]);
      else status.textContent = 'Choose a result from the dropdown before saving.';
    } else if (event.key === 'Escape') {
      event.preventDefault(); ++revision; clearTimeout(timer); if (controller) controller.abort(); showSelected(); close();
    }
  });
  toggle.addEventListener('pointerdown', event => event.preventDefault());
  toggle.addEventListener('click', () => { if (!list.hidden) { showSelected(); close(); } else { input.focus(); open(); } });
  root.addEventListener('focusout', () => setTimeout(() => { if (!root.contains(document.activeElement)) { showSelected(); close(); } }, 0));
  document.addEventListener('pointerdown', event => { if (!root.contains(event.target)) { showSelected(); close(); } });
  saveForm.addEventListener('submit', event => {
    if (input.value !== selectedLabel) { event.preventDefault(); input.focus(); status.textContent = 'Choose a result from the dropdown before saving.'; }
  });
  showSelected();
  status.textContent = 'Open the dropdown for recent posts, or type a title to search.';
})();
