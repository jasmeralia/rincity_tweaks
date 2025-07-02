jQuery(function ($) {

  /* --------------------------------------------------------------------
   * 0. Grab every filter link rendered by **either** the Album or Tags
   *    addons (`a[data-envira-filter]` covers both cases).
   * ------------------------------------------------------------------ */
  $('a[data-envira-filter]').each(function () {
    const selector = this.dataset.enviraFilter;          // ".envira-category-57"
    if (!selector || selector === '*') return;

    const paramVal = selector.replace(/^\./, '');        // "envira-category-57"
    this.href       = buildURL(paramVal);                // add real href
  });

  /* --------------------------------------------------------------------
   * 1. When the page finishes loading, look for ?envira-category=… and
   *    imitate a user click once Envira’s own JS is listening.
   * ------------------------------------------------------------------ */
  $(window).on('load', initDeepLink);                    // load = safe
  $(document).on('envira_albums_init envira_tags_init', initDeepLink);

  function initDeepLink () {
    const cls = new URLSearchParams(location.search).get('envira-category');
    if (!cls) return;

    function tryClick() {
      let $btn = $(`a[data-envira-filter=".${cls}"]`);
      if (!$btn.length) $btn = $(`a[data-envira-filter="${cls}"]`);
      console.log('Looking for filter button:', cls, $btn.length);
      if ($btn.length) {
        // Use native click event for better compatibility
        $btn[0].dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true, view: window }));
        return true;
      }
      return false;
    }

    if (!tryClick()) {
      const observer = new MutationObserver(() => {
        if (tryClick()) observer.disconnect();
      });
      observer.observe(document.body, { childList: true, subtree: true });
      setTimeout(() => observer.disconnect(), 5000);
    }
  }

  function buildURL (cls) {
    const u = new URL(location.href.split('?')[0]);      // strip old qs
    u.searchParams.set('envira-category', cls);
    return u.toString();
  }
});
