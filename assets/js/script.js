document.addEventListener('DOMContentLoaded', function () {
    const panierModal = document.getElementById('panierModal');
    if (!panierModal) return;

    // Update quantity
    panierModal.querySelectorAll('.qtyInput').forEach(input => {
        input.addEventListener('change', function () {
            const tr = this.closest('tr');
            const id = tr.dataset.id;
            const qty = this.value;

            fetch(`/panier/update/${id}/${qty}`, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // նոր subtotal & total հաշվել
                    const price = parseFloat(tr.querySelector('td:nth-child(3)').textContent.replace(',', ''));
                    tr.querySelector('.subtotal').textContent = (price * qty).toFixed(2);
                    document.getElementById('panierTotal').textContent = data.total.toFixed(2);
                }
            });
        });
    });

    // Remove product
    panierModal.querySelectorAll('.removeBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const tr = this.closest('tr');
            const id = tr.dataset.id;

            fetch(`/panier/remove/${id}`, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tr.remove();
                    document.getElementById('panierTotal').textContent = data.total.toFixed(2);
                }
            });
        });
    });
});
