$(document).ready(function() {
    $('#telefone').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');
    $('#cpf').mask('000.000.000-00');
    $('#cnpj').mask('00.000.000/0000-00');
    
    $('#cep-perfil').mask('00000-000');
    $('#telefone-perfil').mask('(00) 00000-0000');
    $('#cpf-perfil').mask('000.000.000-00');

    $('#cep-sistema').mask('00000-000');
    $('#cnpj_sistema').mask('00.000.000/0000-00');
    $('#telefone_fixo').mask('(00) 00000-0000');
    $('#telefone_sistema').mask('(00) 00000-0000');
});