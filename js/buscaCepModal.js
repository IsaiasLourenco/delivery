'use strict';

const exibirMensagemErro = (mensagem) => {
    // Cria um elemento div para mostrar o erro
    const mensagemErro = document.createElement('div');
    mensagemErro.textContent = mensagem;
    mensagemErro.style.color = 'red';
    mensagemErro.style.marginTop = '5px';
    mensagemErro.id = 'mensagem-erro';

    // Adiciona a mensagem logo após o campo de CEP
    const campoCep = document.getElementById('cep-perfil');
    campoCep.parentNode.insertBefore(mensagemErro, campoCep.nextSibling);

    // Remove a mensagem após 3 segundos
    setTimeout(() => {
        if (document.getElementById('mensagem-erro')) {
            document.getElementById('mensagem-erro').remove();
        }
    }, 3000); // 3 segundos
};

const limparCampos = () => {
    document.getElementById('cep-perfil').value = "";
    document.getElementById('rua-perfil').value = "";
    document.getElementById('numero-perfil').value = "";
    document.getElementById('bairro-perfil').value = "";
    document.getElementById('cidade-perfil').value = "";
    document.getElementById('estado-perfil').value = "";
    document.getElementById('cep-perfil').focus();
};

const preencherForm = (endereco) => {
    document.getElementById('cep-perfil').value = endereco.cep;
    document.getElementById('rua-perfil').value = endereco.logradouro;
    document.getElementById('bairro-perfil').value = endereco.bairro;
    document.getElementById('cidade-perfil').value = endereco.localidade;
    document.getElementById('estado-perfil').value = endereco.uf;
};

const cepValido = (cep) => cep.length == 9;
const pesquisarCEP = async () => {
    const cep = document.getElementById('cep-perfil').value;
    const url = `https://viacep.com.br/ws/${cep}/json`;
    if (cepValido(cep)) {
        try {
            const dados = await fetch(url);
            const endereco = await dados.json();
            if (endereco.hasOwnProperty('erro')) {
                exibirMensagemErro('CEP Inexistente!');
                limparCampos();
            } else {
                preencherForm(endereco);
            }
        } catch (error) {
            exibirMensagemErro('Erro ao buscar o CEP.');
            limparCampos();
        }
    } else {
        exibirMensagemErro('CEP Incorreto!');
        limparCampos();
    }
};

document.getElementById('cep-perfil').addEventListener('focusout', pesquisarCEP);