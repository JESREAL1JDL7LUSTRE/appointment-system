<!-- ====== Floating Chatbot Widget ====== -->
<div id="chatbot-widget" style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;align-items:flex-end;gap:12px;">

    <!-- Chat Popup -->
    <div id="chatbot-box" style="display:none;width:340px;background:#fff;border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,0.18);overflow:hidden;flex-direction:column;border:1px solid #e5e7eb;">
        <!-- Header -->
        <div style="background:linear-gradient(135deg,#6366f1,#8b5cf6);padding:14px 18px;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <svg width="16" height="16" fill="white" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                </div>
                <div>
                    <div style="color:#fff;font-weight:600;font-size:14px;">Appointment Assistant</div>
                    <div style="color:rgba(255,255,255,0.75);font-size:11px;">AI-powered support</div>
                </div>
            </div>
            <button id="chatbot-close" style="background:none;border:none;cursor:pointer;color:#fff;opacity:0.8;padding:4px;" title="Close">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="chatbot-messages" style="height:260px;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:10px;background:#f9fafb;">
            <!-- Welcome message -->
            <div style="display:flex;gap:8px;align-items:flex-start;">
                <div style="width:28px;height:28px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                    <svg width="12" height="12" fill="white" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                </div>
                <div style="background:#fff;border-radius:12px 12px 12px 2px;padding:10px 13px;font-size:13px;color:#374151;box-shadow:0 1px 3px rgba(0,0,0,0.08);max-width:230px;line-height:1.5;">
                    Hi! I can help you with appointments, scheduling, and availability. What would you like to know?
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div style="padding:12px;background:#fff;border-top:1px solid #f3f4f6;">
            <div style="display:flex;gap:8px;align-items:center;">
                <input
                    type="text"
                    id="chatbot-input"
                    placeholder="Ask about appointments..."
                    style="flex:1;border:1px solid #e5e7eb;border-radius:24px;padding:9px 16px;font-size:13px;outline:none;background:#f9fafb;color:#111827;transition:border-color .2s;"
                    onFocus="this.style.borderColor='#6366f1'"
                    onBlur="this.style.borderColor='#e5e7eb'"
                />
                <button id="chatbot-send"
                    style="width:38px;height:38px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:opacity .2s;"
                    title="Send"
                >
                    <svg width="16" height="16" fill="white" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Toggle Circle Button -->
    <button id="chatbot-toggle"
        style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);border:none;cursor:pointer;box-shadow:0 4px 18px rgba(99,102,241,0.45);display:flex;align-items:center;justify-content:center;transition:transform .2s,box-shadow .2s;"
        title="Chat with Assistant"
        onMouseEnter="this.style.transform='scale(1.1)';this.style.boxShadow='0 6px 24px rgba(99,102,241,0.55)'"
        onMouseLeave="this.style.transform='scale(1)';this.style.boxShadow='0 4px 18px rgba(99,102,241,0.45)'"
    >
        <svg id="chatbot-icon-open" width="24" height="24" fill="white" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
        <svg id="chatbot-icon-close" width="24" height="24" fill="white" viewBox="0 0 24 24" style="display:none;"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
    </button>
</div>

<script>
(function () {
    const widget   = document.getElementById('chatbot-widget');
    const box      = document.getElementById('chatbot-box');
    const toggle   = document.getElementById('chatbot-toggle');
    const closeBtn = document.getElementById('chatbot-close');
    const input    = document.getElementById('chatbot-input');
    const sendBtn  = document.getElementById('chatbot-send');
    const messages = document.getElementById('chatbot-messages');
    const iconOpen  = document.getElementById('chatbot-icon-open');
    const iconClose = document.getElementById('chatbot-icon-close');

    function openChat() {
        box.style.display = 'flex';
        box.style.flexDirection = 'column';
        iconOpen.style.display = 'none';
        iconClose.style.display = 'block';
        input.focus();
    }

    function closeChat() {
        box.style.display = 'none';
        iconOpen.style.display = 'block';
        iconClose.style.display = 'none';
    }

    toggle.addEventListener('click', () => {
        box.style.display === 'none' ? openChat() : closeChat();
    });
    closeBtn.addEventListener('click', closeChat);

    function addMessage(text, isUser) {
        const wrap = document.createElement('div');
        wrap.style.cssText = 'display:flex;gap:8px;align-items:flex-start;' + (isUser ? 'flex-direction:row-reverse;' : '');

        if (!isUser) {
            const avatar = document.createElement('div');
            avatar.style.cssText = 'width:28px;height:28px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;';
            avatar.innerHTML = '<svg width="12" height="12" fill="white" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>';
            wrap.appendChild(avatar);
        }

        const bubble = document.createElement('div');
        bubble.style.cssText = isUser
            ? 'background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border-radius:12px 12px 2px 12px;padding:10px 13px;font-size:13px;max-width:220px;line-height:1.5;word-wrap:break-word;'
            : 'background:#fff;color:#374151;border-radius:12px 12px 12px 2px;padding:10px 13px;font-size:13px;box-shadow:0 1px 3px rgba(0,0,0,0.08);max-width:220px;line-height:1.5;word-wrap:break-word;';
        bubble.textContent = text;
        wrap.appendChild(bubble);
        messages.appendChild(wrap);
        messages.scrollTop = messages.scrollHeight;
    }

    function addTyping() {
        const wrap = document.createElement('div');
        wrap.id = 'chatbot-typing';
        wrap.style.cssText = 'display:flex;gap:8px;align-items:flex-start;';
        wrap.innerHTML = `
            <div style="width:28px;height:28px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                <svg width="12" height="12" fill="white" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
            </div>
            <div style="background:#fff;border-radius:12px 12px 12px 2px;padding:10px 16px;box-shadow:0 1px 3px rgba(0,0,0,0.08);display:flex;gap:4px;align-items:center;">
                <span style="width:7px;height:7px;background:#9ca3af;border-radius:50%;display:inline-block;animation:chatbot-bounce 1s infinite 0s;"></span>
                <span style="width:7px;height:7px;background:#9ca3af;border-radius:50%;display:inline-block;animation:chatbot-bounce 1s infinite .2s;"></span>
                <span style="width:7px;height:7px;background:#9ca3af;border-radius:50%;display:inline-block;animation:chatbot-bounce 1s infinite .4s;"></span>
            </div>`;
        messages.appendChild(wrap);
        messages.scrollTop = messages.scrollHeight;
    }

    function removeTyping() {
        const el = document.getElementById('chatbot-typing');
        if (el) el.remove();
    }

    async function sendMessage() {
        const text = input.value.trim();
        if (!text) return;

        input.value = '';
        sendBtn.disabled = true;
        addMessage(text, true);
        addTyping();

        try {
            const formData = new FormData();
            formData.append('prompt', text);

            const res = await fetch('/chatbot/ask', { method: 'POST', body: formData });
            const data = await res.json();

            removeTyping();
            addMessage(data.answer || data.error || 'No response.', false);
        } catch (err) {
            removeTyping();
            addMessage('Sorry, something went wrong. Please try again.', false);
        }

        sendBtn.disabled = false;
        input.focus();
    }

    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });
})();
</script>

<style>
@keyframes chatbot-bounce {
    0%, 80%, 100% { transform: scale(0.7); opacity: .5; }
    40% { transform: scale(1); opacity: 1; }
}
</style>
