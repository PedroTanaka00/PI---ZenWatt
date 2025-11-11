class RealTimeMonitor {
    constructor() {
        this.realtimeChart = null;
        this.historicalChart = null;
        this.updateInterval = null;
        this.dataPoints = [];
        this.maxDataPoints = 50;

        this.init();
    }

    init() {
        console.log("🚀 Iniciando RealTimeMonitor...");
        this.initCharts();
        this.startRealTimeUpdates();
        this.setupEventListeners();
    }

    initCharts() {
        console.log("📊 Inicializando gráficos...");

        // Gráfico Tempo Real
        const ctxReal = document.getElementById('realtimeChart');
        if (!ctxReal) {
            console.error("❌ Elemento realtimeChart não encontrado!");
            return;
        }

        this.realtimeChart = new Chart(ctxReal.getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Corrente (A)',
                        data: [],
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.1,
                        yAxisID: 'y',
                        borderWidth: 2
                    },
                    {
                        label: 'Potência (W)',
                        data: [],
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        tension: 0.1,
                        yAxisID: 'y1',
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tempo'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Corrente (A)'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Potência (W)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });

        // Gráfico Histórico
        const ctxHist = document.getElementById('historicalChart');
        if (ctxHist) {
            this.historicalChart = new Chart(ctxHist.getContext('2d'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Potência Média (W)',
                            data: [],
                            borderColor: 'rgb(54, 162, 235)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            fill: true,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Consumo Histórico'
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            title: {
                                display: true,
                                text: 'Potência (W)'
                            }
                        }
                    }
                }
            });
        }

        console.log("✅ Gráficos inicializados!");
    }

    async fetchData() {
        console.log("📡 Buscando dados...");

        try {
            const response = await fetch('../esp32/get_data.php');
            console.log("📋 Resposta recebida:", response.status);

            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }

            const data = await response.json();
            console.log("📊 Dados recebidos:", data);

            if (data.success) {
                this.processData(data.data);
            } else {
                console.error("❌ Erro na resposta:", data.message);
                this.showError(data.message || "Erro ao carregar dados");
            }
        } catch (error) {
            console.error("💥 Erro ao buscar dados:", error);
            this.showError("Erro ao conectar com o servidor");
        }
    }

    processData(newData) {
        console.log("🔄 Processando dados:", newData.length, "registros");

        if (newData.length === 0) {
            console.log("ℹ️ Nenhum dado disponível");
            this.showMessage("Nenhum dado disponível. Aguardando dados do ESP32...");
            return;
        }

        // Atualizar cards com o último dado
        const latest = newData[newData.length - 1];
        this.updateCards(latest);

        // Atualizar gráfico tempo real (últimos pontos)
        const recentData = newData.slice(-this.maxDataPoints);
        this.updateRealTimeChart(recentData);

        // Atualizar tabela (últimos 10 registros)
        this.updateTable(recentData.slice(-10).reverse());

        // Atualizar contador
        const dataCountElement = document.getElementById('dataCount');
        if (dataCountElement) {
            dataCountElement.textContent = newData.length;
        }

        console.log("✅ Dados processados com sucesso!");
    }

    updateCards(latestData) {
        console.log("🎛️ Atualizando cards:", latestData);

        const currentPowerElement = document.getElementById('currentPower');
        const currentCurrentElement = document.getElementById('currentCurrent');

        if (currentPowerElement) {
            currentPowerElement.textContent = latestData.potencia.toFixed(2) + ' W';
        }

        if (currentCurrentElement) {
            currentCurrentElement.textContent = latestData.corrente.toFixed(3) + ' A';
        }
    }

    updateRealTimeChart(data) {
        console.log("📈 Atualizando gráfico tempo real:", data.length, "pontos");

        const labels = data.map(d => {
            try {
                // Formatar timestamp para exibição
                const date = new Date(d.timestamp);
                return date.toLocaleTimeString('pt-BR');
            } catch (e) {
                return d.timestamp; // Fallback se der erro na conversão
            }
        });

        const correnteData = data.map(d => d.corrente);
        const potenciaData = data.map(d => d.potencia);

        if (this.realtimeChart) {
            this.realtimeChart.data.labels = labels;
            this.realtimeChart.data.datasets[0].data = correnteData;
            this.realtimeChart.data.datasets[1].data = potenciaData;
            this.realtimeChart.update('none');
        }
    }

    updateTable(data) {
        console.log("📋 Atualizando tabela:", data.length, "registros");

        const tbody = document.getElementById('corpoTabelaDados');
        if (!tbody) {
            console.error("❌ Tabela não encontrada!");
            return;
        }

        tbody.innerHTML = '';

        data.forEach(item => {
            const tr = document.createElement('tr');

            // Determinar status baseado na potência
            let status, statusClass;
            if (item.potencia > 1000) {
                status = 'Alto Consumo';
                statusClass = 'status-badge warning';
            } else if (item.potencia > 500) {
                status = 'Médio Consumo';
                statusClass = 'status-badge active';
            } else {
                status = 'Normal';
                statusClass = 'status-badge';
            }

            // Formatar timestamp para exibição
            let timestampDisplay;
            try {
                timestampDisplay = new Date(item.timestamp).toLocaleString('pt-BR');
            } catch (e) {
                timestampDisplay = item.timestamp;
            }

            tr.innerHTML = `
                <td>${timestampDisplay}</td>
                <td>${item.corrente.toFixed(3)} A</td>
                <td>${item.potencia.toFixed(2)} W</td>
                <td><span class="${statusClass}">${status}</span></td>
            `;
            tbody.appendChild(tr);
        });
    }

    startRealTimeUpdates() {
        console.log("🔄 Iniciando atualizações em tempo real...");

        // Buscar dados imediatamente
        this.fetchData();

        // Atualizar a cada 5 segundos
        this.updateInterval = setInterval(() => {
            console.log("⏰ Atualização automática...");
            this.fetchData();
        }, 5000);
    }

    setupEventListeners() {
        console.log("🎯 Configurando event listeners...");

        // Abas
        const tabButtons = document.querySelectorAll('.tab-button');
        if (tabButtons.length === 0) {
            console.log("ℹ️ Nenhuma aba encontrada");
            return;
        }

        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const tabId = e.target.getAttribute('data-tab');
                console.log("🔖 Mudando para aba:", tabId);

                // Remover active de todos
                document.querySelectorAll('.tab-button').forEach(btn =>
                    btn.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(pane =>
                    pane.classList.remove('active'));

                // Adicionar active ao atual
                e.target.classList.add('active');
                const targetPane = document.getElementById(`tab-${tabId}`);
                if (targetPane) {
                    targetPane.classList.add('active');
                }

                // Carregar dados históricos se necessário
                if (tabId === 'historico') {
                    this.loadHistoricalData();
                }
            });
        });

        console.log("✅ Event listeners configurados!");
    }

    async loadHistoricalData() {
        console.log("📚 Carregando dados históricos...");

        try {
            const response = await fetch('../esp32/get_data.php');
            const data = await response.json();

            if (data.success && data.data.length > 0) {
                this.updateHistoricalChart(data.data);
            } else {
                console.log("ℹ️ Nenhum dado histórico disponível");
            }
        } catch (error) {
            console.error('❌ Erro ao carregar dados históricos:', error);
        }
    }

    updateHistoricalChart(data) {
        if (!this.historicalChart || data.length === 0) return;

        console.log("📊 Atualizando gráfico histórico com", data.length, "registros");

        // Agrupar dados por hora
        const hourlyData = this.groupByHour(data);

        this.historicalChart.data.labels = Object.keys(hourlyData);
        this.historicalChart.data.datasets[0].data = Object.values(hourlyData);
        this.historicalChart.update();
    }

    groupByHour(data) {
        const hourly = {};

        data.forEach(item => {
            try {
                const date = new Date(item.timestamp);
                const hour = `${date.getHours().toString().padStart(2, '0')}:00`;

                if (!hourly[hour]) {
                    hourly[hour] = [];
                }
                hourly[hour].push(item.potencia);
            } catch (e) {
                console.warn("⚠️ Erro ao processar timestamp:", item.timestamp);
            }
        });

        // Calcular média por hora
        Object.keys(hourly).forEach(hour => {
            const values = hourly[hour];
            hourly[hour] = values.length > 0 ?
                values.reduce((a, b) => a + b, 0) / values.length : 0;
        });

        return hourly;
    }

    showError(message) {
        console.error("❌ Exibindo erro:", message);

        // Criar notificação de erro
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f56565;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            z-index: 10000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-left: 4px solid #e53e3e;
            max-width: 400px;
        `;
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-exclamation-circle"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(notification);

        // Remover após 5 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }

    showMessage(message) {
        console.log("💬 Exibindo mensagem:", message);

        const tbody = document.getElementById('corpoTabelaDados');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem; color: #666;">
                        <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                        ${message}
                    </td>
                </tr>
            `;
        }
    }

    // Método para parar as atualizações
    stop() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            console.log("🛑 Monitoramento parado");
        }
    }
}

// Exportar dados
function exportData() {
    console.log("💾 Exportando dados...");
    window.open('../esp32/data.csv', '_blank');
}

// Função para teste manual
function testManual() {
    console.log("🧪 Teste manual iniciado...");
    const monitor = new RealTimeMonitor();
    return monitor;
}

// Inicializar quando DOM carregar
document.addEventListener('DOMContentLoaded', () => {
    console.log("🏠 DOM Carregado - Iniciando monitor...");
    window.monitor = new RealTimeMonitor();
});

// Expor para testes no console
window.RealTimeMonitor = RealTimeMonitor;
window.testManual = testManual;
window.exportData = exportData;

// Adicionar estilos para os badges se não existirem
const additionalStyles = document.createElement('style');
additionalStyles.textContent = `
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-badge.active {
        background: #c6f6d5;
        color: #276749;
    }
    
    .status-badge.warning {
        background: #fed7d7;
        color: #c53030;
    }
    
    .status-badge {
        background: #e2e8f0;
        color: #4a5568;
    }
`;
document.head.appendChild(additionalStyles);