async function clickVote(element, status, userId, csrf) {
    let url = '/file/vote';
    let voteBlock = element.closest('.vote-block');
    let fileId = voteBlock.getAttribute('data-file-id');
    let statusText = (status==='1') ? 'Approved' : 'Rejected';

    try {
        const response = await fetch(url, {
            method: "post",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrf
            },
            body: JSON.stringify({
                'vote': status,
                'user_id': userId,
                'file_id': fileId
            })
        });

        const result = await response.json();

        if (result.success) {

            voteBlock.parentNode.removeChild(voteBlock);  

            let statusTeg = document.getElementById(`file-${fileId}`).querySelector(`div[name="status-${userId}"]`);

            statusTeg.textContent = statusText;
        }

    } catch (error) {
        
    }
}

function hideModal() {
    document.getElementById('modal').style.display = "none";
}

function showModal() {
    document.getElementById('modal').style.display = "block";
}
