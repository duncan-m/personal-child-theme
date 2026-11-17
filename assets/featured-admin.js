(() => {
  'use strict';
  const input = document.getElementById('dm-featured-search');
  const select = document.getElementById('dm-featured-post');
  const status = document.getElementById('dm-featured-status');
  const form = document.getElementById('dm-featured-search-form');
  if (!input || !select || !status || !form || !window.dmFeatured) return;
  let timer;
  let controller;
  let revision = 0;
  const search = async () => {
    const request = ++revision;
    if (controller) controller.abort();
    controller = new AbortController();
    const url = new URL(window.dmFeatured.url, window.location.href);
    url.searchParams.set('action', 'dm_search_featured');
    url.searchParams.set('nonce', window.dmFeatured.nonce);
    url.searchParams.set('search', input.value.trim());
    status.textContent = 'Searching posts…';
    select.setAttribute('aria-busy', 'true');
    try {
      const response = await fetch(url, { credentials: 'same-origin', signal: controller.signal });
      const result = await response.json();
      if (!response.ok || !result.success || !Array.isArray(result.data)) throw new Error('Search failed');
      if (request !== revision) return;
      // Preserve the current (even unsaved) choice while the result list changes.
      const selected = select.value;
      const selectedText = select.selectedOptions[0] ? select.selectedOptions[0].textContent : '';
      const options = [new Option('Keep existing homepage block setting', 'block'), new Option('Automatic — latest published writing post', '0')];
      if (selected !== 'block' && selected !== '0' && !result.data.some(post => String(post.id) === selected)) {
        options.push(new Option(selectedText, selected));
      }
      result.data.forEach(post => options.push(new Option(post.label, String(post.id))));
      select.replaceChildren(...options);
      select.value = selected;
      status.textContent = result.data.length
        ? `${input.value.trim() ? 'Matching' : 'Recent'} posts: ${result.data.length}${result.data.length === 20 ? ' (up to 20; refine your search for more)' : ''}. Choose an essay and save.`
        : 'No matching published writing posts. Try another title. Your selection is unchanged.';
    } catch (error) {
      if (error.name !== 'AbortError' && request === revision) {
        status.textContent = 'Could not search posts. Try again or reload this page. Your selection is unchanged.';
      }
    } finally {
      if (request === revision) select.removeAttribute('aria-busy');
    }
  };
  input.addEventListener('input', () => {
    clearTimeout(timer);
    // Invalidate requests immediately, even during the debounce delay.
    ++revision;
    if (controller) controller.abort();
    timer = setTimeout(search, 300);
  });
  form.addEventListener('submit', event => {
    event.preventDefault();
    clearTimeout(timer);
    search();
  });
})();
