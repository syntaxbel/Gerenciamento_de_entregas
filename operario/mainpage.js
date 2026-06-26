let entregas = [];
let indiceEdicao = -1;
let indiceSelecionado = -1;


const modal = document.getElementById("modalAdicionar");
const tbody = document.getElementById("tabela-corpo");
const form = document.getElementById("formEntrega");
const tituloModal = document.querySelector(".modal-box h3");
const btnEditarSidebar = document.getElementById("btnEditarSidebar"); 
const contadorTotal = document.getElementById("contador-total"); // Conexão com o novo contador

/* Configuração do botão de editar */
btnEditarSidebar.addEventListener("click", function() {
    if (indiceSelecionado === -1) {
        alert("Por favor, clique em uma linha da tabela para selecionar primeiro.");
    } else {
        prepararEdicao(indiceSelecionado);
    }
});

/* Funções do Modal */
function abrirModal() {
    modal.style.display = "flex";
}

function fecharModal() {
    modal.style.display = "none";
    form.reset();
    indiceEdicao = -1;
    tituloModal.innerText = "Nova Entrega";
}

/* Seleção e Edição */
function selecionarLinha(index) {
    indiceSelecionado = index;
    carregarTabela(); 
}

function prepararEdicao(index) {
    indiceEdicao = index;
    const entrega = entregas[index];

    document.getElementById("inputNome").value = entrega.nome;
    document.getElementById("inputID").value = entrega.id;
    document.getElementById("inputData").value = entrega.data;
    document.getElementById("inputStatus").value = entrega.status;

    tituloModal.innerText = "Editar Entrega";
    abrirModal();
}

/* Exclusão */
function excluirEntrega(index) {
    if(confirm("Tem certeza que deseja excluir esta entrega?")) {
        entregas.splice(index, 1);
        indiceSelecionado = -1; 
        carregarTabela();
    }
}

/* Lógica de Cores do Status */
function obterClasseStatus(status) {
    const classes = {
        "Embalado": "status-embalado",
        "Transportando": "status-transportando",
        "Entregado": "status-entregado",
        "Interrompido": "status-interrompido"
    };
    return classes[status] || "";
}

/* Submit do Formulário (Salvar/Editar) */
form.addEventListener("submit", function(event) {
    event.preventDefault();

    const novaEntrega = {
        nome: document.getElementById("inputNome").value,
        id: document.getElementById("inputID").value,
        data: document.getElementById("inputData").value,
        status: document.getElementById("inputStatus").value
    };

    if (indiceEdicao >= 0) {
        entregas[indiceEdicao] = novaEntrega;
    } else {
        entregas.push(novaEntrega);
    }

    carregarTabela();
    fecharModal();
    mostrarMensagemSucesso(); 
});

/**
 * ATUALIZAÇÃO PRINCIPAL: carregarTabela agora atualiza o contador
 */
function carregarTabela() {
    tbody.innerHTML = "";

    // Atualiza o número no contador da Navbar
    if (contadorTotal) {
        contadorTotal.innerText = entregas.length;
    }

    if (entregas.length === 0) {
        tbody.innerHTML = "<tr><td colspan='5' style='text-align:center; padding: 40px; color: #A3AED0;'>Nenhuma entrega cadastrada.</td></tr>";
        return;
    }

    entregas.forEach((entrega, index) => {
        const classeCor = obterClasseStatus(entrega.status);
        const classeSelecionada = (index === indiceSelecionado) ? "selecionada" : "";

        const linha = `
            <tr class="${classeSelecionada}" onclick="selecionarLinha(${index})">
                <td><strong>${entrega.nome}</strong></td>
                <td>${entrega.id}</td>
                <td>${entrega.data}</td>
                <td><span class="status-badge ${classeCor}">${entrega.status}</span></td>
                <td style="text-align: center;">

                    </button>
                </td>
            </tr>
        `;
        tbody.innerHTML += linha;
    });
}

function mostrarMensagemSucesso() {
    const msg = document.getElementById("msgSucesso");
    msg.style.display = "block";
    setTimeout(() => { msg.style.display = "none"; }, 3000);
}

/* Inicialização */
carregarTabela();

function atualizarContador() {
    // Seleciona todas as linhas (tr) dentro do corpo da tabela
    const linhas = document.querySelectorAll('#tabela-corpo tr');
    const contadorElemento = document.getElementById('contador-total');
    
    // Verifica se a primeira linha é a mensagem de "Nenhuma entrega"
    // Se a tabela tiver apenas 1 linha e ela contiver "Nenhuma entrega", o total é 0
    if (linhas.length === 1 && linhas[0].innerText.includes("Nenhuma entrega")) {
        contadorElemento.innerText = "0";
    } else {
        contadorElemento.innerText = linhas.length;
    }
}

// Chame essa função ao carregar a página e ao final de cada cadastro com sucesso
document.addEventListener('DOMContentLoaded', atualizarContador);
