// Configurações globais - Detecta automaticamente o ambiente
const API_URL = window.location.hostname === 'localhost' && window.location.port !== '80' ? 'api/' : 'api/';
const USUARIO_ID_FIXO = 1;

// Variáveis globais
let rankingData = [];
let currentOrderBy = 'pontos_totais';

// Inicialização quando o documento estiver pronto
$(document).ready(function() {
    // Lógica do Formulário
    $('#desafio-form').on('submit', function(e) {
        e.preventDefault();
        salvarDesafio();
    });

    // Busca em tempo real
    $('#search-user').on('input', function() {
        aplicarFiltros();
    });

    // Carga inicial
    carregarTudo();
});

// Funções do Modal de Feedback
function mostrarModal(feedback) {
    $('#feedback-texto').html(
        `Você ganhou <strong>${feedback.pontos_ganhos} pontos</strong>!<br>` +
        `Seu streak agora é de <strong>${feedback.novo_streak} dias</strong> 🔥`
    );
    const badgesDiv = $('#feedback-badges');
    badgesDiv.empty();
    if (feedback.novos_badges && feedback.novos_badges.length > 0) {
        badgesDiv.append('<h4>✨ Novas Conquistas:</h4>');
        feedback.novos_badges.forEach(badge => {
            badgesDiv.append(`
                <div class="badge-conquistado">
                    <img src="${badge.icone_url}" alt="${badge.nome}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjUiIGN5PSIyNSIgcj0iMjUiIGZpbGw9IiNGRkQ3MDAiLz4KPHN2ZyB3aWR0aD0iMzAiIGhlaWdodD0iMzAiIHZpZXdCb3g9IjAgMCAzMCAzMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4PSIxMCIgeT0iMTAiPgo8cGF0aCBkPSJNMTUgMkwyMC4wOTAyIDExLjkwOThMMzAgMTVMMjAuMDkwMiAxOC4wOTAyTDE1IDI4TDkuOTA5ODMgMTguMDkwMkwwIDE1TDkuOTA5ODMgMTEuOTA5OEwxNSAyWiIgZmlsbD0iI0ZGRiIvPgo8L3N2Zz4KPC9zdmc+'">
                    <div>
                        <strong>${badge.nome}</strong><br>
                        <small>${badge.descricao}</small>
                    </div>
                </div>
            `);
        });
    }
    $('#feedback-modal').show();
}

// Funções de CRUD e Gamificação
function carregarDesafios() {
    $.ajax({
        url: API_URL + 'desafios_simple.php',
        method: 'GET',
        success: function(data) {
            const lista = $('#desafios-lista');
            lista.empty();
            if (data && data.length > 0) {
                data.forEach(d => {
                    lista.append(`
                        <div class="desafio" id="desafio-${d.id}">
                            <div class="acoes">
                                <i class="fas fa-pencil-alt" title="Editar" onclick="editarDesafio(${d.id})"></i>
                                <i class="fas fa-trash-alt" title="Deletar" onclick="deletarDesafio(${d.id})"></i>
                            </div>
                            <button onclick="concluirDesafio(${d.id})">✅ Concluir</button>
                            <strong>${d.titulo}</strong> (${d.area_conhecimento})<br>
                            <small>${d.descricao}</small><br>
                            <span style="color: #28a745; font-weight: bold;">+${d.pontos} pontos</span>
                        </div>
                    `);
                });
            } else {
                lista.append('<p>Nenhum desafio disponível. Adicione um abaixo! 👇</p>');
            }
        },
        error: function() {
            $('#desafios-lista').html('<p style="color: red;">❌ Erro ao carregar os desafios.</p>');
        }
    });
}

function carregarRanking(orderBy = 'pontos_totais') {
    currentOrderBy = orderBy;
    $('.filters button').removeClass('active');
    (orderBy === 'pontos_totais') ? $('#btn-ord-pontos').addClass('active') : $('#btn-ord-streak').addClass('active');
    
    $.ajax({
        url: API_URL + `ranking_simple.php?orderBy=${orderBy}`,
        method: 'GET',
        success: function(data) {
            rankingData = data || [];
            exibirRanking(rankingData);
        },
        error: function() {
            $('#ranking-body').html('<tr><td colspan="5" style="color: red;">❌ Erro ao carregar o ranking.</td></tr>');
        }
    });
}

function exibirRanking(data) {
    const tbody = $('#ranking-body');
    tbody.empty();
    
    if (data && data.length > 0) {
        data.forEach((user, index) => {
            const posicao = index + 1;
            let emoji = '';
            if (posicao === 1) emoji = '🥇';
            else if (posicao === 2) emoji = '🥈';
            else if (posicao === 3) emoji = '🥉';
            
            // Contar badges do usuário (simplificado)
            const badgeCount = user.pontos_totais > 0 ? Math.floor(user.pontos_totais / 50) + 1 : 0;
            const badgesHtml = badgeCount > 0 ? `🏆 ${badgeCount}` : '0';
            
            tbody.append(`
                <tr>
                    <td>${emoji} ${posicao}º</td>
                    <td>${user.nome}</td>
                    <td>${user.pontos_totais}</td>
                    <td>${user.streak_atual}</td>
                    <td>${badgesHtml}</td>
                </tr>
            `);
        });
    } else {
        tbody.append('<tr><td colspan="5">Ninguém no ranking ainda. 🤔</td></tr>');
    }
}

// Funções de busca e filtros
function aplicarFiltros() {
    const searchTerm = $('#search-user').val().toLowerCase();
    const minPoints = parseInt($('#min-points').val()) || 0;
    const minStreak = parseInt($('#min-streak').val()) || 0;
    
    let filteredData = rankingData.filter(user => {
        const matchesSearch = user.nome.toLowerCase().includes(searchTerm);
        const matchesPoints = user.pontos_totais >= minPoints;
        const matchesStreak = user.streak_atual >= minStreak;
        
        return matchesSearch && matchesPoints && matchesStreak;
    });
    
    exibirRanking(filteredData);
}

function limparFiltros() {
    $('#search-user').val('');
    $('#min-points').val('');
    $('#min-streak').val('');
    exibirRanking(rankingData);
}

// Função para concluir desafio com animação
function concluirDesafio(desafioId) {
    // Adicionar animação de conclusão
    const desafioElement = $(`#desafio-${desafioId}`);
    desafioElement.addClass('desafio-concluido');
    
    // Adicionar badge de "Concluído"
    desafioElement.append('<div class="concluido-badge">✅ Concluído</div>');
    
    // Fazer a requisição AJAX
    $.ajax({
        url: API_URL + 'concluir_desafio_simple.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ usuario_id: USUARIO_ID_FIXO, desafio_id: desafioId }),
        success: function(feedback) {
            // Mostrar modal após um pequeno delay para a animação
            setTimeout(() => {
                mostrarModal(feedback);
            }, 800);
        },
        error: function(xhr) {
            const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Erro desconhecido.';
            alert('❌ Erro ao concluir desafio: ' + errorMsg);
            
            // Remover animação em caso de erro
            desafioElement.removeClass('desafio-concluido');
            desafioElement.find('.concluido-badge').remove();
        }
    });
}

function salvarDesafio() {
    const id = $('#desafio-id').val();
    const url = id ? `${API_URL}desafios_simple.php?id=${id}` : `${API_URL}desafios_simple.php`;
    const method = id ? 'PUT' : 'POST';
    const desafioData = {
        titulo: $('#titulo').val(),
        descricao: $('#descricao').val(),
        area_conhecimento: $('#area_conhecimento').val(),
        pontos: parseInt($('#pontos').val())
    };

    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        data: JSON.stringify(desafioData),
        success: function() {
            alert(`✅ Desafio ${id ? 'atualizado' : 'criado'} com sucesso!`);
            cancelarEdicao();
            carregarTudo();
        },
        error: function() {
            alert('❌ Erro ao salvar desafio.');
        }
    });
}

function editarDesafio(id) {
    $.get(`${API_URL}desafios_simple.php?id=${id}`, function(desafio) {
        $('#desafio-id').val(desafio.id);
        $('#titulo').val(desafio.titulo);
        $('#descricao').val(desafio.descricao);
        $('#area_conhecimento').val(desafio.area_conhecimento);
        $('#pontos').val(desafio.pontos);
        $('#btn-salvar').html('🔄 Atualizar Desafio');
        $('#btn-cancelar').show();
        document.getElementById('gerenciamento-section').scrollIntoView({ behavior: 'smooth' });
    });
}

function deletarDesafio(id) {
    if (confirm('🗑️ Tem certeza que deseja deletar este desafio?')) {
        $.ajax({
            url: `${API_URL}desafios_simple.php?id=${id}`,
            method: 'DELETE',
            success: function() {
                alert('✅ Desafio deletado com sucesso!');
                carregarTudo();
            },
            error: function() {
                alert('❌ Erro ao deletar desafio.');
            }
        });
    }
}

function cancelarEdicao() {
    $('#desafio-form')[0].reset();
    $('#desafio-id').val('');
    $('#btn-salvar').html('💾 Salvar Desafio');
    $('#btn-cancelar').hide();
}

function carregarTudo(orderBy = 'pontos_totais') {
    carregarDesafios();
    carregarRanking(orderBy);
}

function fecharModal() {
    $('#feedback-modal').hide();
    carregarTudo();
}

// Exportar funções para uso global
window.concluirDesafio = concluirDesafio;
window.editarDesafio = editarDesafio;
window.deletarDesafio = deletarDesafio;
window.cancelarEdicao = cancelarEdicao;
window.carregarTudo = carregarTudo;
window.fecharModal = fecharModal;
window.aplicarFiltros = aplicarFiltros;
window.limparFiltros = limparFiltros;
