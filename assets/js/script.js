document.querySelectorAll('.js-add-to-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;

        fetch('{{ path("panier_add", {"id": "ID_REPLACE"}) }}'.replace('ID_REPLACE', id))
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert(data.message + ' (Total dans le panier: ' + data.count + ')');
                }
            });
    });
});