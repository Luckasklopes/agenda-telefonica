// Funções para abrir/fechar modal de adição
function abrirModalAdicionar() {
  document.getElementById("modalAdicionar").style.display = "block";
}

function fecharModalAdicionar() {
  document.getElementById("modalAdicionar").style.display = "none";
}

// Funções para abrir/fechar modal de edição
function abrirModalEditar() {
  document.getElementById("modalEditar").style.display = "block";
}

function fecharModalEditar() {
  document.getElementById("modalEditar").style.display = "none";
}

function abrirModalExcluir(id) {
  document.getElementById("modalExcluir").style.display = "block";
  document.getElementById("idExcluir").value = id;
}

function fecharModalExcluir() {
  document.getElementById("modalExcluir").style.display = "none";
}

// Fecha modal ao clicar fora dele
window.onclick = function (event) {
  const modalAdicionar = document.getElementById("modalAdicionar");
  const modalEditar = document.getElementById("modalEditar");

  if (modalAdicionar && event.target === modalAdicionar) {
    modalAdicionar.style.display = "none";
  }

  if (modalEditar && event.target === modalEditar) {
    modalEditar.style.display = "none";
  }

  if (modalExcluir && event.target === modalExcluir) {
    modalExcluir.style.display = "none";
  }
};

// Abre automaticamente o modal de edição se ele existir no carregamento da página
window.onload = function () {
  const modalEditar = document.getElementById("modalEditar");
  if (modalEditar) {
    abrirModalEditar();
  }
};

function formatarTelefone(valor) {
  // Remove tudo que não for número
  valor = valor.replace(/\D/g, "");

  // Aplica a máscara
  if (valor.length > 0) {
    valor = "(" + valor;
  }
  if (valor.length > 3) {
    valor = valor.slice(0, 3) + ") " + valor.slice(3);
  }
  if (valor.length > 10) {
    valor = valor.slice(0, 10) + "-" + valor.slice(10, 14);
  }
  if (valor.length > 15) {
    valor = valor.slice(0, 15); // Limita o tamanho máximo
  }
  return valor;
}

const botaoTema = document.getElementById('toggle-tema');

botaoTema.addEventListener('click', () => {
  const html = document.documentElement;
  const temaAtual = html.getAttribute('data-theme');

  if (temaAtual === 'escuro') {
    html.removeAttribute('data-theme'); // volta ao claro
  } else {
    html.setAttribute('data-theme', 'escuro');
  }
});
