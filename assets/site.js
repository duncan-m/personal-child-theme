(() => {
  const bar = document.querySelector('.dm-contact-bar');
  if (!bar) return;
  const update = () => document.documentElement.style.setProperty('--dm-bar-height', `${Math.ceil(bar.getBoundingClientRect().height)}px`);
  update();
  if ('ResizeObserver' in window) new ResizeObserver(update).observe(bar);
  else window.addEventListener('resize', update, { passive: true });
})();
