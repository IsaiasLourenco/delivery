<?php
$pag = 'cotacao';
?>

<a type="button" class="btn btn-dark" onclick="inserir()">Novo Vínculo
    <i class="fa fa-plus" aria-hidden="true"></i>
</a>

<div class="bs-example widget-shadow pdg-15" id="listar"></div>

<!-- Modal Inserir -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="titulo_inserir"></span></h4>
                <button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Fornecedor</label>
                            <select class="form-control sel2" name="fornecedor_id" id="fornecedor_id">
                                <?php
                                $query = $pdo->query("SELECT * FROM fornecedores ORDER BY nome ASC");
                                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($res as $row) {
                                    echo '<option value="' . $row['id'] . '">' . $row['nome'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Produto</label>
                            <select class="form-control sel2" name="produto_id" id="produto_id">
                                <?php
                                $query = $pdo->query("SELECT * FROM produtos ORDER BY nome ASC");
                                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($res as $row) {
                                    echo '<option value="' . $row['id'] . '">' . $row['nome'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Valor de Compra</label>
                            <input type="text" class="form-control" name="valor_compra" id="valor_compra" required>
                        </div>
                        <div class="col-md-4">
                            <label>Prazo de Entrega</label>
                            <input type="text" class="form-control" name="prazo_entrega" id="prazo_entrega">
                        </div>
                        <div class="col-md-4">
                            <label>Principal?</label>
                            <select class="form-control" name="principal" id="principal">
                                <option value="1">Sim</option>
                                <option value="0" selected>Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Observações</label>
                            <textarea class="form-control" name="observacoes" id="observacoes"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div id="mensagem" class="centro texto-menor"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal -->

<script type="text/javascript">
    var pag = "<?= $pag ?>";

    $(document).ready(function () {
        listar();
        $('.sel2').select2({ dropdownParent: $('#modalForm') });

        document.getElementById("valor_compra").addEventListener("input", function () {
            formatarMoedaInput(this);
        });
    });

    function listar() {
        $.ajax({
            url: 'paginas/' + pag + "/listar.php",
            method: 'POST',
            data: $('#form').serialize(),
            dataType: "html",
            success: function (result) {
                $("#listar").html(result);
                $('#mensagem-excluir').text('');
            }
        });
    }

    function inserir() {
        $('#mensagem').text('');
        $('#titulo_inserir').text('Inserir Vínculo');
        $('#modalForm').modal('show');
        limparCampos();
    }

    function editar(id, fornecedor, produto, valor, prazo, principal, observacoes) {
        $('#id').val(id);
        $('#fornecedor_id').val(fornecedor).change();
        $('#produto_id').val(produto).change();
        $('#valor_compra').val(valor);
        $('#prazo_entrega').val(prazo);
        $('#principal').val(principal).change();
        $('#observacoes').val(observacoes);

        $('#titulo_inserir').text('Editar Vínculo');
        $('#modalForm').modal('show');
    }

    function excluir(id) {
        $.ajax({
            url: 'paginas/' + pag + "/excluir.php",
            method: 'POST',
            data: { id },
            dataType: "text",
            success: function (mensagem) {
                if (mensagem.trim() == "Excluído com Sucesso") {
                    listar();
                } else {
                    $('#mensagem-excluir').addClass('text-danger')
                    $('#mensagem-excluir').text(mensagem)
                }
            },
        });
    }

    function limparCampos() {
        $('#id').val('');
        $('#fornecedor_id').val('').change();
        $('#produto_id').val('').change();
        $('#valor_compra').val('');
        $('#prazo_entrega').val('');
        $('#principal').val('0').change();
        $('#observacoes').val('');
    }

    function formatarMoedaInput(input) {
        let valor = input.value.replace(/\D/g, "");
        valor = (valor / 100).toFixed(2) + "";
        valor = valor.replace(".", ",");
        valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        input.value = "R$ " + valor;
    }

    $("#form").submit(function () {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'paginas/' + pag + "/salvar.php",
            type: 'POST',
            data: formData,
            success: function (mensagem) {
                $('#mensagem').text('');
                $('#mensagem').removeClass('text-danger text-success')
                if (mensagem.trim() == "Salvo com Sucesso") {
                    $('#btn-fechar').click();
                    listar();
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