
const editarNomeBtn = document.getElementById("editarNome");
const nomeUsuario = document.getElementById("nome");
const modal = document.getElementById("modal");
const modalTitle = document.getElementById("modalTitle");
const modalForm = document.getElementById("modalForm");
const fecharModal = document.getElementById("fecharModal");
const inputNome = document.getElementById("inputNome");
const inputDescricao = document.getElementById("inputDescricao");
const inputImagem = document.getElementById("inputImagem");

let tipoAtual = ""; 


function abrirModal(tipo) {
  modal.style.display = "flex";
  modalTitle.textContent = `Adicionar ${tipo}`;
  tipoAtual = tipo;
}


function fechar() {
  modal.style.display = "none";
  modalForm.reset();
}

// Botão de editar nome
editarNomeBtn.addEventListener("click", () => {
  const novoNome = prompt("Digite o novo nome:");
  if (novoNome) {
    nomeUsuario.textContent = novoNome;
  }
});

// Botões (Competências, Certificados, Trabalho, Formação)
document.querySelectorAll(".adicionar").forEach((botao) => {
  botao.addEventListener("click", () => {
    abrirModal(botao.dataset.tipo);
  });
});

// Formulário
modalForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const nome = inputNome.value.trim();
  const descricao = inputDescricao.value.trim();
  const imagem = inputImagem.files[0];

  if (!nome || !descricao) {
    alert("Preencha todos os campos!");
    return;
  }

 
  const li = document.createElement("li");
  li.innerHTML = `<strong>${nome}</strong> - ${descricao}`;

  // Adiciona imagem
  if (imagem) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const img = document.createElement("img");
      img.src = e.target.result;
      img.alt = nome;
      li.appendChild(img);
    };
    reader.readAsDataURL(imagem);
  }

 
  if (tipoAtual === "Competência") {
    document.getElementById("listaCompetencias").appendChild(li);
  } else if (tipoAtual === "Certificado") {
    document.getElementById("listaCertificados").appendChild(li);
  } else if (tipoAtual === "Trabalho") {
    document.getElementById("listaTrabalho").appendChild(li);
  } else if (tipoAtual === "Formação") {
    document.getElementById("listaFormacao").appendChild(li);
  }

  fechar();
});

// Fechar modal
fecharModal.addEventListener("click", fechar);

// Fechar modal 
window.addEventListener("click", (e) => {
  if (e.target === modal) {
    fechar();
  }
});