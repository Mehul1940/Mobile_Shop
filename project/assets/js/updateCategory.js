// Open modal with category data
function openModal(category) {
    document.getElementById("CATEGORY_ID").value = category.CATEGORY_ID;
    document.getElementById("CATEGORY_NAME").value = category.CATEGORY_NAME;
    document.getElementById("updateModal").style.display = "block";
}

// Close modal
function closeModal() {
    document.getElementById("updateModal").style.display = "none";
}
