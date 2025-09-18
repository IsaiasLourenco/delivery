<?php
$pag = 'produtos';

?>
<a type="button" class="btn btn-dark" onclick="inserir()">Novo Produto
    <i class="fa fa-plus" aria-hidden="true"></i>
</a>

<div class="bs-example widget-shadow pdg-15" id="listar">

</div>

<!-- Modal Inserir-->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="text" class="form-control" id="nome-produto" name="nome" required>
                        </div>
                        <div class="col-md-6">
                            <label for="categoria">Categoria</label>
                            <select class="form-control" name="categoria" id="categoria-produto">
                                <?php
                                $query = $pdo->query("SELECT * FROM categorias ORDER BY nome asc");
                                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                $total_reg = @count($res);
                                if ($total_reg > 0) {
                                    for ($i = 0; $i < $total_reg; $i++) {
                                        foreach ($res[$i] as $key => $value) {
                                        }
                                        echo '<option value="' . $res[$i]['id'] . '">' . $res[$i]['nome'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="0">Cadastre uma Categoria</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" name="descricao" id="descricao-produto" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="valor_compra">Valor de Compra</label>
                            <input type="text" class="form-control" id="valor_compra-produto" name="valor_compra" required>
                        </div>
                        <div class="col-md-4">
                            <label for="valor_venda">Valor de Venda</label>
                            <input type="text" class="form-control" id="valor_venda-produto" name="valor_venda" required>
                        </div>
                        <div class="col-md-4">
                            <label for="estoque">Estoque</label>
                            <input type="text" class="form-control" id="estoque-produto" name="estoque" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="nivel_estoque">Estoque Mínimo</label>
                            <input type="text" class="form-control" id="nivel_estoque-produto" name="nivel_estoque" required>
                        </div>
                        <div class="col-md-4">
                            <label for="ativo">Ativo</label>
                            <select class="form-control" name="ativo" id="ativo-produto">
                                <option value="Sim" selected>Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tem_estoque">Tem Estoque?</label>
                            <select class="form-control" name="tem_estoque" id="tem_estoque-produto">
                                <option value="Sim" selected>Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="foto">Foto</label>
                            <input type="file" class="form-control" id="foto-produto" name="foto" onchange="carregarImgProduto()">
                        </div>
                        <div class="col-md-6">
                            <img src="./images/produtos/sem-foto.jpg" alt="Foto do produto" style="width: 80px;" id="target-produto">
                        </div>
                        <input type="hidden" name="id" id="id-produto">
                    </div>
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

<!-- Modal Dados-->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="nome_dados"><span id="nome_dados-produto"></span></h4>
                <button id="btn-fechar-dados" type="button" class="close mg-t--20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row br-btt">
                    <div class="col-md-6">
                        <span><b>Descrição: </b></span>
                        <span id="descricao_dados-produto"></span>
                    </div>
                    <div class="col-md-6">
                        <span><b>Estoque: </b></span>
                        <span id="estoque_dados-produto"></span>
                    </div>
                </div>
                <div class="row br-btt">
                    <div class="col-md-4">
                        <span><b>Nível Estoque: </b></span>
                        <span id="nivel_estoque_dados-produto"></span>
                    </div>
                    <div class="col-md-4">
                        <span><b>Ativo: </b></span>
                        <span id="ativo_dados-produto"></span>
                    </div>
                    <div class="col-md-4">
                        <span><b>Tem Estoque? </b></span>
                        <span id="tem_estoque_dados-produto"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 centro">
                        <img width="250px" id="target_mostrar-produto">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fim Modal Dados-->

<!-- Modal Saida-->
<div class="modal fade" id="modalSaida" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="nome_saida"></span></h4>
                <button id="btn-fechar-saida" type="button" class="close mg-t--20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-saida">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="number" class="form-control" id="quantidade_saida" name="quantidade_saida" placeholder="Quantidade Saída" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <input type="text" class="form-control" id="motivo_saida" name="motivo_saida" placeholder="Motivo Saída" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                    <input type="hidden" id="id_saida" name="id">
                    <input type="hidden" id="estoque_saida" name="estoque">
                </form>
                <br>
                <div id="mensagem-saida" class="centro texto-menor"></div>
            </div>
        </div>
    </div>
</div>
<!-- Fim Modal Saida-->

<!-- Modal Entrada-->
<div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="nome_entrada"></span></h4>
                <button id="btn-fechar-entrada" type="button" class="close mg-t--20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-entrada">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="number" class="form-control" id="quantidade_entrada" name="quantidade_entrada" placeholder="Quantidade Entrada" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <input type="text" class="form-control" id="motivo_entrada" name="motivo_entrada" placeholder="Motivo Entrada" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                    <input type="hidden" id="id_entrada" name="id">
                    <input type="hidden" id="estoque_entrada" name="estoque">
                </form>
                <br>
                <small>
                    <div id="mensagem-entrada" class="centro texto-menor"></div>
                </small>
            </div>
        </div>
    </div>
</div>
<!-- Fim Modal Entrada-->

<!-- Variável Página -->
<script type="text/javascript">
    var pag = "<?= $pag ?>"
</script>
<!-- JavaScript para chamar o CRUD da tabela -->
<script src="js/ajax.js"></script>

<!-- SCRIPT TROCA FOTO -->
<script type="text/javascript">
    function carregarImgProduto() {
        var target = document.getElementById('target-produto');
        var file = document.querySelector("#foto-produto").files[0];
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

<!-- AJAX SALVA EDITA USUARIO -->
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
<!-- FIM AJAX SALVA EDITA USUARIO -->

<!-- Função para formatar o valor para moeda brasileira -->
<script>
    function formatarMoedaInput(input) {
        let valor = input.value.replace(/\D/g, ""); // só números
        valor = (valor / 100).toFixed(2) + ""; // duas casas decimais
        valor = valor.replace(".", ","); // vírgula como separador decimal
        valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // pontos como separadores de milhar
        input.value = "R$ " + valor;
    }

    // Aplicar nos inputs
    document.getElementById("valor_compra-produto").addEventListener("input", function() {
        formatarMoedaInput(this);
    });

    document.getElementById("valor_venda-produto").addEventListener("input", function() {
        formatarMoedaInput(this);
    });
</script>
<!-- Fim Função para formatar o valor para moeda brasileira -->

<!-- Saida de produtos -->
<script type="text/javascript">
    $("#form-saida").submit(function() {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'paginas/' + pag + "/saida.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem-saida').text('');
                $('#mensagem-saida').removeClass('text-danger text-success')
                if (mensagem.trim() == "Salvo com Sucesso") {
                    $('#btn-fechar-saida').click();
                    listar();
                } else {
                    $('#mensagem-saida').addClass('text-danger')
                    $('#mensagem-saida').text(mensagem)
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
</script>
<!-- Fim Saida de produtos -->

<!-- Entrada de produtos -->
<script type="text/javascript">
    $("#form-entrada").submit(function() {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'paginas/' + pag + "/entrada.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem-entrada').text('');
                $('#mensagem-entrada').removeClass('text-danger text-success')
                if (mensagem.trim() == "Salvo com Sucesso") {
                    $('#btn-fechar-entrada').click();
                    listar();
                } else {
                    $('#mensagem-entrada').addClass('text-danger')
                    $('#mensagem-entrada').text(mensagem)
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
</script>
<!-- Fim Entrada de produtos -->