export default {
  async fetch(request, env, ctx) {
    try {
      const url = new URL(request.url);
      let pathname = url.pathname;

      // Serve index.html for root
      if (pathname === '/' || pathname === '') {
        pathname = '/index.html';
      }

      // Try to fetch the static asset
      try {
        const asset = await env.ASSETS.fetch(request);
        if (asset.status !== 404) {
          // For HTML files, set correct content type
          if (pathname.endsWith('.html')) {
            return new Response(asset.body, {
              status: asset.status,
              headers: {
                ...Object.fromEntries(asset.headers),
                'Content-Type': 'text/html; charset=utf-8',
                'Cache-Control': 'public, max-age=3600',
              },
            });
          }
          return asset;
        }
      } catch (e) {
        // Continue to manual serving
      }

      // Manual fallback - serve index.html for HTML requests
      const newUrl = new URL(request.url);
      newUrl.pathname = pathname;
      
      const assetRequest = new Request(newUrl.toString(), {
        method: request.method,
        headers: request.headers,
      });

      try {
        return await env.ASSETS.fetch(assetRequest);
      } catch (e) {
        return new Response('Not Found', { status: 404 });
      }
    } catch (error) {
      console.error('Error:', error);
      return new Response('Internal Server Error', { status: 500 });
    }
  },
};
