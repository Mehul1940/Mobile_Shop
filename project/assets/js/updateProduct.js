function openModal(product) {
    document.getElementById('PRODUCT_ID').value = product.PRODUCT_ID;
    document.getElementById('PRODUCT_NAME').value = product.PRODUCT_NAME;
    document.getElementById('PRICE').value = product.PRICE;
    document.getElementById('CATEGORY_ID').value = product.CATEGORY_ID;
    document.getElementById('BRAND_ID').value = product.BRAND_ID;

    // Display current image in the modal
    const imagePreview = document.getElementById('IMAGE_PREVIEW');
    if (product.P_IMG) {
        imagePreview.style.display = 'block';
        imagePreview.src = product.P_IMG;
    } else {
        imagePreview.style.display = 'none';
    }

    document.getElementById('updateModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('updateModal').style.display = 'none';
}