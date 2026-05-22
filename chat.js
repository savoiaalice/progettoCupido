const idAltro= CHAT_ID_ALTRO;
const chatBox=document.getElementById("chat-box");

function caricaChat(){
    fetch("caricaChat.php?id=" + idAltro)
    .then(r => r.text())
    .then(html => {
        chatBox.innerHTML = html;
        chatBox.scrollTop = chatBox.scrollHeight;
    });
}
document.getElementById("formMessaggio").addEventListener("submit", function(e){
    e.preventDefault();

    const testo = document.getElementById("testo").value.trim();
    if (!testo) return;

    const data = new URLSearchParams();
    data.append("testo", testo);
    data.append("id_dest", idAltro);

    fetch("inviaMessaggio.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: data.toString()
    }).then(() => {
        document.getElementById("testo").value = "";
        caricaChat();
    });
});
setInterval(caricaChat, 1500);
caricaChat();