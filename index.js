export default {
  async fetch(request) {
    return new Response('Moonlit Aura Website', { 
      status: 200,
      headers: { 'Content-Type': 'text/plain' }
    });
  }
};
