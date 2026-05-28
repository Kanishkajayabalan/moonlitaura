export default {
  async fetch(request, env) {
    const url = new URL(request.url);
    let pathname = url.pathname;

    // Serve index.html for root
    if (pathname === '/' || pathname === '') {
      pathname = '/index.html';
    }

    // Build the request URL for the asset
    const assetUrl = new URL(pathname, url.origin).toString();
    
    try {
      // Use ASSETS to fetch the requested file
      const response = await env.ASSETS.fetch(new Request(assetUrl));
      
      if (response.ok || response.status === 304) {
        return response;
      }
      
      // If not found and not an HTML file, return 404
      if (response.status === 404) {
        return new Response('Not Found', { status: 404 });
      }
      
      return response;
    } catch (error) {
      console.error('Error fetching asset:', pathname, error);
      return new Response('Internal Server Error', { status: 500 });
    }
  },
};
