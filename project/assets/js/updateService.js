function openModal(serviceId, serviceName, description) {
    document.getElementById('modal-service-id').value = serviceId;
    document.getElementById('modal-service-name').value = serviceName;
    document.getElementById('modal-description').value = description;
    document.getElementById('updateModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('updateModal').style.display = 'none';
}

// Close modal when clicking outside the content
window.onclick = function (event) {
    const modal = document.getElementById('updateModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};