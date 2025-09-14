(function () {
  'use strict';

  function showCepMsg(campo, texto) {
    // remove mensagem anterior no mesmo contexto
    const sibling = campo.parentNode.querySelector('.cep-msg');
    if (sibling) sibling.remove();
    const div = document.createElement('div');
    div.className = 'cep-msg';
    div.textContent = texto;
    div.style.color = 'red';
    div.style.marginTop = '6px';
    campo.parentNode.insertBefore(div, campo.nextSibling);
    setTimeout(() => { if (div) div.remove(); }, 3000);
  }

  function findFieldInContext(context, base, prefix) {
    // 1) tenta id exato com prefixo: e.g. rua-perfil
    if (prefix) {
      const id = `${base}-${prefix}`;
      const byId = document.getElementById(id);
      if (byId && (context === document || context.contains(byId))) return byId;
    }
    // 2) procura por input/textarea/select cujo id contenha a base dentro do contexto
    const byIdContains = context.querySelector(`input[id*="${base}"], textarea[id*="${base}"], select[id*="${base}"]`);
    if (byIdContains) return byIdContains;
    // 3) procura por name contendo a base
    const byName = context.querySelector(`input[name*="${base}"], textarea[name*="${base}"], select[name*="${base}"]`);
    if (byName) return byName;
    return null;
  }

  function formatCepDisplay(cepDigits) {
    if (cepDigits.length === 8) return cepDigits.slice(0,5) + '-' + cepDigits.slice(5);
    return cepDigits;
  }

  async function onCepBlur(e) {
    const cepEl = e.currentTarget;
    const raw = cepEl.value.replace(/\D/g, '');
    const prefix = (cepEl.id && cepEl.id.includes('-')) ? cepEl.id.split('-').slice(1).join('-') : '';
    const context = cepEl.closest('form') || cepEl.closest('.modal') || document;

    if (raw.length !== 8) {
      showCepMsg(cepEl, 'CEP inválido!');
      // limpa campos relacionados no mesmo contexto
      ['rua','logradouro','bairro','cidade','estado','uf','cep'].forEach(k=>{
        const f = findFieldInContext(context, k, prefix);
        if (f) f.value = '';
      });
      return;
    }

    try {
      const resp = await fetch(`https://viacep.com.br/ws/${raw}/json/`);
      const data = await resp.json();
      if (data.erro) {
        showCepMsg(cepEl, 'CEP não encontrado!');
        return;
      }

      // mapear campos a preencher (tenta vários nomes comuns)
      const mappings = {
        cep: formatCepDisplay(data.cep || raw),
        rua: data.logradouro || '',
        logradouro: data.logradouro || '',
        bairro: data.bairro || '',
        cidade: data.localidade || '',
        estado: data.uf || '',
        uf: data.uf || ''
      };

      Object.entries(mappings).forEach(([key, val]) => {
        const field = findFieldInContext(context, key, prefix);
        if (field) field.value = val;
      });

    } catch (err) {
      showCepMsg(cepEl, 'Erro ao consultar CEP.');
      console.error('Erro consulta CEP:', err);
    }
  }

  // inicia: aplica listener em todos os inputs cujo id comece por "cep-"
  document.addEventListener('DOMContentLoaded', function () {
    const ceps = document.querySelectorAll('input[id^="cep-"]');
    ceps.forEach(i => i.addEventListener('blur', onCepBlur));
  });

})();
