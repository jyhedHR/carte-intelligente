// ── AI CHAT ──
// toggleChat and sendMessage are global because HTML calls them via onclick=""

window.toggleChat = function(){
  const chat = document.getElementById('ai-chat');
  if (!chat) return;
  chat.style.display = (chat.style.display === 'flex') ? 'none' : 'flex';
}

window.sendMessage = function() {
  const input = document.getElementById('chat-input');
  const body = document.getElementById('chat-body');
  if (!input || !body || !input.value.trim()) return;

  body.innerHTML += `<p style="text-align:right;margin:12px 0;color:var(--text);"><strong>Vous :</strong> ${input.value}</p>`;
  input.value = '';
  body.scrollTop = body.scrollHeight;

  setTimeout(() => {
    body.innerHTML += `<p style="margin:12px 0;color:var(--text2);"><strong>Assistant :</strong> Merci pour votre question. Je vous aide à remplir le formulaire pour la démarche sélectionnée.</p>`;
    body.scrollTop = body.scrollHeight;
  }, 800);
}
