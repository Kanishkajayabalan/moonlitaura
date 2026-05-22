export default {
  async fetch(request, env) {
    const url = new URL(request.url);
    
    // If path is root, serve index.html
    if (url.pathname === '/') {
      url.pathname = '/index.html';
    }
    
    // Serve static assets
    return env.ASSETS.fetch(url.toString());
  }
};
