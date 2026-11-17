(function (wp) {
 'use strict';
 const el = wp.element.createElement;
 const Fragment = wp.element.Fragment;
 const ServerSideRender = wp.serverSideRender;
 ['writing', 'interviews'].forEach(function (kind) {
  wp.blocks.registerBlockType('dm/' + kind, {
   edit: function (props) {
    const a = props.attributes;
    const records = wp.data.useSelect(function (select) {
     return kind === 'writing' ? select('core').getEntityRecords('postType', 'post', { per_page: 100, status: 'publish', orderby: 'date', order: 'desc' }) : [];
    }, []);
    const choices = [{ label: 'Latest eligible writing post (automatic)', value: '0' }];
    (records || []).forEach(function (post) {
     const doc = new DOMParser().parseFromString(post.title.rendered, 'text/html');
     choices.push({ label: doc.body.textContent || '(Untitled)', value: String(post.id) });
    });
    if (a.featuredId && !choices.some(function(c) { return c.value === String(a.featuredId); })) {
     choices.push({ label: 'Selected post #' + a.featuredId, value: String(a.featuredId) });
    }
    return el(Fragment, null,
     el(wp.blockEditor.InspectorControls, null,
      el(wp.components.PanelBody, { title: 'Editorial settings' },
       el(wp.components.TextControl, { label: 'Section heading', value: a.heading, onChange: function (heading) { props.setAttributes({ heading: heading }); } }),
       kind === 'writing' && el(wp.components.SelectControl, { label: 'Featured essay', help: 'Published writing posts only. Interview selections fall back to the latest writing post. Latest 100 posts listed; use the ID field for older posts.', value: String(a.featuredId || 0), options: choices, onChange: function (value) { props.setAttributes({ featuredId: Number(value) }); } }),
       kind === 'writing' && el(wp.components.TextControl, { type: 'number', label: 'Featured post ID (optional)', min: 0, value: a.featuredId || 0, onChange: function (value) { props.setAttributes({ featuredId: Math.max(0, parseInt(value,10) || 0) }); } }),
       kind === 'writing' && el(wp.components.TextControl, { label: 'Writing archive URL (optional)', help: 'Defaults to the Posts page selected in Settings → Reading.', value: a.archiveUrl || '', onChange: function (archiveUrl) { props.setAttributes({ archiveUrl: archiveUrl }); } })
      )
     ),
     el('div', wp.blockEditor.useBlockProps(), el(ServerSideRender, { block: 'dm/' + kind, attributes: a }))
    );
   },
   save: function () { return null; }
  });
 });
})(window.wp);
