<div class="d-flex">
    <a href="#popup-1" class="text-dark carrinho">
        <i class="bi bi-cart-fill"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge badge-pill bg-danger pilula">
            3
        </span>
    </a>
</div>

<div id="popup-1" class="overlay">
    <div class="popup">
        <div class="row">
            <div class="col-9">
                <h3 class="titulo-popup">3 Itens Adicionados</h3>
            </div>
            <div class="col-3">
                <a class="close" href="#">&times;</a>
            </div>
        </div>
        <hr class="linha">
        <div class="conteudo-popup">
            Aqui vamos colocar depois o conteúdo desse popup trazendo os itens que forem adicionados no carrinho
        </div>
    </div>
</div>

<script>
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (window.location.hash === '#popup-1') {
                window.location.hash = ''; // remove o hash e fecha o popup
            }
        }
    });
</script>