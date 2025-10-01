<?php
$pag = 'categorias';

?>
<a type="button" class="btn btn-dark" onclick="inserir()">Nova Categoria
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
                <button id="btn-fechar" type="button" class="close mg-t--20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome-cat" name="nome" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cor">Cor</label>
                            <select class="form-control" name="cor" id="cor-cat">
                                <option value="azul" selected>Azul</option>
                                <option value="rosa">Rosa</option>
                                <option value="azul-escuro">Azul-Escuro</option>
                                <option value="verde">Verde</option>
                                <option value="roxo">Roxo</option>
                                <option value="vermelho">Vermelho</option>
                                <option value="verde-escuro">Verde-Escuro</option>
                                <option value="laranja">Laranja</option>
                                <option value="amarelo">Amarelo</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" name="descricao" id="descricao-cat" required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="ativo">Ativo</label>
                            <select class="form-control" name="ativo" id="ativo-cat">
                                <option value="Sim" selected>Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="foto">Foto</label>
                            <input type="file" class="form-control" id="foto-cat" name="foto" onchange="carregarImgCategoria()">
                        </div>
                        <div class="col-md-2">
                            <img src="./images/categorias/sem-foto.jpg" alt="Foto da categoria" style="width: 80px;" id="target-cat">
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id-cat">
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

<!-- SCRIPT TROCA FOTO -->
<script type="text/javascript">
    function carregarImgCategoria() {
        var target = document.getElementById('target-cat');
        var file = document.querySelector("#foto-cat").files[0];
        var reader = new FileReader();
        reader.onloadend = function() {
            target.src = reader.result;
        };
        if (file) {
            reader.readAsDataURL(file);
        } else {
            target.src = "";
        }
    }
</script>
<!-- FIM SCRIPT TROCA FOTO -->

<!-- AJAX SALVA EDITA CATEGORIA -->
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