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

<!-- Modal Variações-->
<div class="modal fade" id="modalVariacoes" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="titulo_nome_var"></span></h4>
                <button id="btn-fechar-var" type="button" class="close mg-t-20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-var">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sigla">Sigla</label>
                                <input maxlength="5" type="text" class="form-control" id="sigla" name="sigla">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input maxlength="35" type="text" class="form-control" id="nome_var" name="nome" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="valor">Valor</label>
                                <input type="text" class="form-control" id="valor_var" name="valor" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <input maxlength="50" type="text" class="form-control" id="descricao_var" name="descricao">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="descricao">Ativo</label>
                                <select class="form-control" name="ativo" id="ativo_var">
                                    <option value="Sim" selected>Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end justify-content-between mg-t-20 ">
                            <button type="submit" class="btn btn-primary" id="btn-salvar-var">Salvar</button>
                            <button type="button" class="btn btn-danger" id="btn-novo-var" onclick="novoVar()">Novo</button>
                        </div>
                    </div>
                    <input type="hidden" id="id_var" name="id">
                    <input type="hidden" id="id_variacao" name="id_var">
                </form>
                <br>
                <div id="mensagem-var" class="centro texto-menor"></div>
                <hr>
                <div id="listar-var"></div>
            </div>
        </div>
    </div>
</div>
<!-- Fim Modal Variações -->

<!-- Modal Ingredientes -->
<div class="modal fade" id="modalIngredientes" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="titulo_nome_ing"></span></h4>
                <button id="btn-fechar-ing" type="button" class="close mg-t-20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-ing">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input maxlength="50" type="text" class="form-control" id="nome_ing" name="nome" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ativo">Ativo</label>
                                <select class="form-control" name="ativo" id="ativo_ing">
                                    <option value="Sim" selected>Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end justify-content-between mg-t-20 ">
                            <button type="submit" class="btn btn-primary" id="btn-salvar-ing">Salvar</button>
                            <button type="button" class="btn btn-danger" id="btn-novo-ing" onclick="novoIng()">Novo</button>
                        </div>
                    </div>
                    <input type="hidden" id="id_ing" name="id"> <!--produto-->
                    <input type="hidden" id="id_ingrediente" name="id_ing">
                </form>
                <br>
                <div id="mensagem-ing" class="centro texto-menor"></div>
                <hr>
                <div id="listar-ing"></div>
            </div>
        </div>
    </div>
</div>
<!-- Fim Modal Ingredientes -->

<!-- Modal Adicionais -->
<div class="modal fade" id="modalAdicionais" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="titulo_nome_ad"></span></h4>
                <button id="btn-fechar-ad" type="button" class="close mg-t-20" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-ad">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input maxlength="50" type="text" class="form-control" id="nome_ad" name="nome" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="valor">Valor</label>
                                <input maxlength="50" type="text" class="form-control" id="valor_ad" name="valor" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ativo">Ativo</label>
                                <select class="form-control" name="ativo" id="ativo_ad">
                                    <option value="Sim" selected>Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end justify-content-between mg-t-20 ">
                            <button type="submit" class="btn btn-primary" id="btn-salvar-ad">Salvar</button>
                            <button type="button" class="btn btn-danger" id="btn-novo-ad" onclick="novoAd()">Novo</button>
                        </div>
                    </div>
                    <input type="hidden" id="id_ad" name="id"> <!--produto-->
                    <input type="hidden" id="id_adicional" name="id_ad">
                </form>
                <br>
                <div id="mensagem-ad" class="centro texto-menor"></div>
                <hr>
                <div id="listar-ad"></div>
            </div>
        </div>
    </div>
</div>
<!-- Fim Modal Adicionais -->

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

    document.getElementById("valor_var").addEventListener("input", function() {
        formatarMoedaInput(this);
    });

    document.getElementById("valor_ad").addEventListener("input", function() {
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

<!-- Inserir e Editar Variações de produtos -->
<script type="text/javascript">
    $("#form-var").submit(function() {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'paginas/' + pag + "/variacoes.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem-var').text('');
                $('#mensagem-var').removeClass('text-danger text-success')
                if (mensagem.trim() == "Salvo com Sucesso") {
                    listarVariacoes($('#id_var').val());
                    limparCamposVar();
                } else {
                    $('#mensagem-var').addClass('text-danger')
                    $('#mensagem-var').text(mensagem)
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });

    function novoVar() {
        limparCamposVar();
    }
</script>
<!-- Fim Inserir e Editar Variações de produtos -->

<!-- Listar Variações de produtos -->
<script type="text/javascript">
    function limparCamposVar() {
        $('#nome_var').val('');
        $('#valor_var').val('');
        $('#sigla').val('');
        $('#descricao_var').val('');
        $('#id_variacao').val('');
        $('#btn-salvar-var').text('Salvar');
        $('#btn-novo-var').hide();
    }

    function listarVariacoes(id) {
        $.ajax({
            url: 'paginas/' + pag + "/listar-variacoes.php",
            method: 'POST',
            data: {
                id: id
            },
            dataType: "html",
            success: function(result) {
                $("#listar-var").html(result);
                $("#mensagem-ecluir-var").text('');
            }
        });
    }
</script>
<!-- Fim Listar Variações de produtos -->

<!-- Excluir Variações de produtos -->
<script type="text/javascript">
    function excluirVar(id) {
        $.ajax({
            url: 'paginas/' + pag + "/excluir-variacoes.php",
            method: 'POST',
            data: {
                id
            },
            dataType: "text",
            success: function(mensagem) {
                if (mensagem.trim() == "Excluído com Sucesso") {
                    listarVariacoes($('#id_var').val());
                    limparCamposVar();
                } else {
                    $('#mensagem-excluir-var').addClass('text-danger')
                    $('#mensagem-excluir-var').text(mensagem)
                }
            },
        });
    }
</script>
<!-- Fim Excluir Variações de produtos -->

<!-- Ativar Variações de produtos -->
<script type="text/javascript">
    function ativarVar(id, acao) {
        $.ajax({
            url: 'paginas/' + pag + "/mudar-status-variacoes.php",
            method: 'POST',
            data: {
                id,
                acao
            },
            dataType: 'text',

            success: function(mensagem) {
                if (mensagem.trim() == "Alterado com sucesso") {
                    listarVariacoes($('#id_var').val());
                    limparCamposVar();
                } else {
                    $('#mensagem-excluir-var').addClass('text-danger')
                    $('#mensagem-excluir-var').text(mensagem)
                }
            },
        });
    }
</script>
<!-- Fim Ativar Variações de produtos -->

<!-- Inserir e Editar Ingredientes de produtos -->
<script type="text/javascript">
    $("#form-ing").submit(function() {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'paginas/' + pag + "/ingredientes.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem-ing').text('');
                $('#mensagem-ing').removeClass('text-danger text-success')
                if (mensagem.trim() == "Salvo com Sucesso") {
                    listarIngredientes($('#id_ing').val());
                    limparCamposIng();
                } else {
                    $('#mensagem-ing').addClass('text-danger')
                    $('#mensagem-ing').text(mensagem)
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });

    function novoIng() {
        limparCamposIng();
    }
</script>
<!-- Fim Inserir e Editar Ingredientes de produtos -->

<!-- Listar Ingredientes de produtos -->
<script type="text/javascript">
    function limparCamposIng() {
        $('#nome_ing').val('');
        $('#id_ingrediente').val('');
        $('#btn-salvar-ing').text('Salvar');
        $('#btn-novo-ing').hide();
    }

    function listarIngredientes(id) {
        $.ajax({
            url: 'paginas/' + pag + "/listar-ingredientes.php",
            method: 'POST',
            data: {
                id: id
            },
            dataType: "html",
            success: function(result) {
                $("#listar-ing").html(result);
                $("#mensagem-ecluir-ing").text('');
            }
        });
    }
</script>
<!-- Fim Listar Ingredientes de produtos -->

<!-- Excluir Ingredientes de produtos -->
<script type="text/javascript">
    function excluirIng(id) {
        $.ajax({
            url: 'paginas/' + pag + "/excluir-ingredientes.php",
            method: 'POST',
            data: {
                id
            },
            dataType: "text",
            success: function(mensagem) {
                if (mensagem.trim() == "Excluído com Sucesso") {
                    listarIngredientes($('#id_ing').val());
                    limparCamposIng();
                } else {
                    $('#mensagem-excluir-ing').addClass('text-danger')
                    $('#mensagem-excluir-ing').text(mensagem)
                }
            },
        });
    }
</script>
<!-- Fim Excluir Ingredientes de produtos -->

<!-- Ativar Ingredientes de produtos -->
<script type="text/javascript">
    function ativarIng(id, acao) {
        $.ajax({
            url: 'paginas/' + pag + "/mudar-status-ingredientes.php",
            method: 'POST',
            data: {
                id,
                acao
            },
            dataType: 'text',

            success: function(mensagem) {
                if (mensagem.trim() == "Alterado com sucesso") {
                    listarIngredientes($('#id_ing').val());
                    limparCamposIng();
                } else {
                    $('#mensagem-excluir-ing').addClass('text-danger')
                    $('#mensagem-excluir-ing').text(mensagem)
                }
            },
        });
    }
</script>
<!-- Fim Ativar Ingredientes de produtos -->

<!-- Inserir e Editar Adicionais de produtos -->
<script type="text/javascript">
    $("#form-ad").submit(function() {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'paginas/' + pag + "/adicionais.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem-ad').text('');
                $('#mensagem-ad').removeClass('text-danger text-success')
                if (mensagem.trim() == "Salvo com Sucesso") {
                    listarAdicionais($('#id_ad').val());
                    limparCamposAd();
                } else {
                    $('#mensagem-ad').addClass('text-danger')
                    $('#mensagem-ad').text(mensagem)
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });

    function novoAd() {
        limparCamposAd();
    }
</script>
<!-- Fim Inserir e Editar Adicionais de produtos -->

<!-- Listar Adicionais de produtos -->
<script type="text/javascript">
    function limparCamposAd() {
        $('#nome_ad').val('');
        $('#valor_ad').val('');
        $('#id_adicional').val('');
        $('#btn-salvar-ad').text('Salvar');
        $('#btn-novo-ad').hide();
    }

    function listarAdicionais(id) {
        $.ajax({
            url: 'paginas/' + pag + "/listar-adicionais.php",
            method: 'POST',
            data: {
                id: id
            },
            dataType: "html",
            success: function(result) {
                $("#listar-ad").html(result);
                $("#mensagem-ecluir-ad").text('');
            }
        });
    }
</script>
<!-- Fim Listar Adicionais de produtos -->

<!-- Excluir Adicionais de produtos -->
<script type="text/javascript">
    function excluirAd(id) {
        $.ajax({
            url: 'paginas/' + pag + "/excluir-adicionais.php",
            method: 'POST',
            data: {
                id
            },
            dataType: "text",
            success: function(mensagem) {
                if (mensagem.trim() == "Excluído com Sucesso") {
                    listarAdicionais($('#id_ad').val());
                    limparCamposAd();
                } else {
                    $('#mensagem-excluir-ad').addClass('text-danger')
                    $('#mensagem-excluir-ad').text(mensagem)
                }
            },
        });
    }
</script>
<!-- Fim Excluir Adicionais de produtos -->

<!-- Ativar Adicionais de produtos -->
<script type="text/javascript">
    function ativarAd(id, acao) {
        $.ajax({
            url: 'paginas/' + pag + "/mudar-status-adicionais.php",
            method: 'POST',
            data: {
                id,
                acao
            },
            dataType: 'text',

            success: function(mensagem) {
                if (mensagem.trim() == "Alterado com sucesso") {
                    listarAdicionais($('#id_ad').val());
                    limparCamposAd();
                } else {
                    $('#mensagem-excluir-ad').addClass('text-danger')
                    $('#mensagem-excluir-ad').text(mensagem)
                }
            },
        });
    }
</script>
<!-- Fim Ativar Adicionais de produtos -->