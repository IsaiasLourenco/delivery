<?php
$pag = 'cargos';

?>
<a type="button" class="btn btn-dark" onclick="inserir()">Novo Cargo
    <i class="fa fa-plus" aria-hidden="true"></i>
</a>

<div class="bs-example widget-shadow pdg-15" id="listar">

</div>

<!-- Modal Inserir-->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="titulo_inserir"></span></h4>
                <button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome-cargo" name="nome" required>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div id="mensagem" class="centro"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- Fim Modal Inserir-->

<!-- Variável Página -->
<script type="text/javascript">
    var pag = "<?= $pag ?>"
</script>
<!-- JavaScript para chamar o CRUD da tabela -->
<script src="js/ajax.js"></script>

<!-- AJAX SALVA EDITA CARGO -->
<script type="text/javascript">
    $("#form").submit(function() {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'paginas/' + pag + "/salvar.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem').text('');
                $('#mensagem').removeClass('text-danger text-success')
                if (mensagem.trim() == "Salvo com Sucesso") {
                    $('#btn-fechar').click();
                    location.reload();
                } else {
                    $('#mensagem').addClass('text-danger')
                    $('#mensagem').text(mensagem)
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
</script>
<!-- FIM AJAX SALVA EDITA CARGO -->