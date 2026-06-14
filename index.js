export default {
  async fetch(request) {
    const url = new URL(request.url);
    
    // For now, serve a simple response
    return new Response('Moonlit Aura Website - Static site serving setup', {
      status: 200,
      headers: {
        'Content-Type': 'text/html;charset=UTF-8'
      }
    });
  }
};
