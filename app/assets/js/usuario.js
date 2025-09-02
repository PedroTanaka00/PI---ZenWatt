// =====================
// Helpers visuais
// =====================
const ease = 'easeOutQuart';
const defaultAnim = { duration: 900, easing: ease };

function gradientFill(ctx, colorTop = '#04b600', colorBottom = '#ffffff') {
  const gradient = ctx.createLinearGradient(0, 0, 0, 280);
  gradient.addColorStop(0, colorTop);
  gradient.addColorStop(1, colorBottom);
  return gradient;
}

// =====================
// Charts
// =====================
const lineCtx = document.getElementById('lineChart').getContext('2d');
const barCtx = document.getElementById('barChart').getContext('2d');
const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
const areaCtx = document.getElementById('areaChart').getContext('2d');
const aparelhosCtx = document.getElementById('aparelhosChart').getContext('2d');
const projectionCtx = document.getElementById('projectionChart').getContext('2d');

// Dados iniciais
const dias = Array.from({length: 30}, (_, i) => `${i+1}`);
const consumoDiario = dias.map((_, i) => Math.round(8 + Math.sin(i/3)*2 + Math.random()*3 + (i%7===0?5:0)));

const lineChart = new Chart(lineCtx, {
  type: 'line',
  data: {
    labels: dias,
    datasets: [{
      label: 'kWh',
      data: consumoDiario,
      borderWidth: 2,
      borderColor: '#019400',
      pointRadius: 0,
      tension: 0.35,
      fill: true,
      backgroundColor: gradientFill(lineCtx, 'rgba(4,182,0,.28)', 'rgba(4,182,0,0)')
    }]
  },
  options: {
    responsive: true,
    animation: defaultAnim,
    interaction: { mode: 'index', intersect: false },
    plugins: {
      legend: { display: false },
      tooltip: { usePointStyle: true }
    },
    scales: {
      x: { grid: { display: false } },
      y: { grid: { color: '#eef2ef' }, beginAtZero: true }
    }
  }
});

const barChart = new Chart(barCtx, {
  type: 'bar',
  data: {
    labels: ['Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set'],
    datasets: [{
      label: 'kWh',
      data: [180, 220, 200, 240, 210, 250],
      borderWidth: 0,
      backgroundColor: ['#04b600','#00a800','#019400','#028400','#026400','#014700']
    }]
  },
  options: {
    responsive: true,
    animation: { ...defaultAnim, delay: (c) => c.dataIndex * 40 },
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { display:false } },
      y: { grid: { color:'#eef2ef' }, beginAtZero:true }
    }
  }
});

const doughnutChart = new Chart(doughnutCtx, {
  type: 'doughnut',
  data: {
    labels: ['Pessoal', 'Negócios'],
    datasets: [{ data: [60, 40], backgroundColor: ['#04b600', '#e2f76a'] }]
  },
  options: {
    cutout: '60%',
    animation: { animateRotate: true, animateScale: true, ...defaultAnim },
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});

const areaChart = new Chart(areaCtx, {
  type: 'line',
  data: {
    labels: ['00-03','03-06','06-09','09-12','12-15','15-18','18-21','21-24'],
    datasets: [{
      label: 'kWh',
      data: [2, 1.2, 2.8, 3.2, 4.5, 5.8, 7.5, 4.1],
      borderColor: '#04b600',
      backgroundColor: gradientFill(areaCtx, 'rgba(4,182,0,.25)', 'rgba(4,182,0,0)'),
      fill: true,
      tension: 0.35,
      pointRadius: 3
    }]
  },
  options: {
    responsive: true,
    animation: defaultAnim,
    plugins: { legend: { display: false } },
    scales: { x: { grid: { display:false } }, y: { beginAtZero: true, grid: { color:'#eef2ef' } } }
  }
});

// Aparelhos (dinâmico com o formulário)
let aparelhosLabels = ['Geladeira', 'Ar-condicionado', 'Iluminação', 'TV'];
let aparelhosData = [55, 80, 22, 18]; // kWh/mês (exemplo inicial)

const aparelhosChart = new Chart(aparelhosCtx, {
  type: 'polarArea',
  data: {
    labels: aparelhosLabels,
    datasets: [{ data: aparelhosData, backgroundColor: ['#04b600','#02a000','#019400','#7bd66f'] }]
  },
  options: {
    responsive: true,
    animation: { animateRotate: true, animateScale: true, duration: 1000, easing: ease },
    plugins: { legend: { position: 'bottom' } },
    scales: {}
  }
});

// Projeção (linha com tendência)
const projMeses = ['Out','Nov','Dez','Jan','Fev','Mar'];
const projData = [230, 235, 240, 238, 245, 250];
const projectionChart = new Chart(projectionCtx, {
  type: 'line',
  data: {
    labels: projMeses,
    datasets: [{
      label: 'kWh (estimado)',
      data: projData,
      borderColor: '#026400',
      backgroundColor: gradientFill(projectionCtx, 'rgba(4,182,0,.20)', 'rgba(4,182,0,0)'),
      fill: true,
      tension: 0.35,
      pointRadius: 4
    }]
  },
  options: {
    responsive: true,
    animation: defaultAnim,
    plugins: { legend: { display: false } },
    scales: { x: { grid: { display:false }}, y: { grid:{ color:'#eef2ef'}, beginAtZero:false } }
  }
});

// Botões de "atualizar" com animações e leve aleatoriedade
document.getElementById('btnAtualizar1').addEventListener('click', () => {
  const newData = consumoDiario.map(v => Math.max(4, Math.round(v * (0.9 + Math.random()*0.3))));
  lineChart.data.datasets[0].data = newData;
  lineChart.update();
});

document.getElementById('btnAtualizar2').addEventListener('click', () => {
  barChart.data.datasets[0].data = barChart.data.datasets[0].data.map(v => Math.max(120, Math.round(v * (0.9 + Math.random()*0.35))));
  barChart.update();
});

// =====================
// Calendário (modal)
// =====================
const days = document.querySelectorAll(".calendar-grid .day");
const modal = document.getElementById("calendarModal");
const closeModal = document.getElementById("closeModal");
const modalTitle = document.getElementById("modalTitle");
const modalText = document.getElementById("modalText");

days.forEach(day => {
  day.addEventListener("click", () => {
    const d = day.textContent.trim();
    modalTitle.textContent = `Dia ${d} - Resumo`;
    modalText.textContent = `No dia ${d}, seu consumo foi de ${ (8 + Math.random()*6).toFixed(1) } kWh. Veja o histórico completo para comparar e encontrar oportunidades de economia.`;
    modal.classList.add('show');
  });
});

closeModal.addEventListener("click", () => modal.classList.remove("show"));
window.addEventListener("click", (e) => { if (e.target === modal) modal.classList.remove("show"); });

// =====================
// Formulário de aparelhos
// =====================
const form = document.getElementById('formAparelho');
const tabela = document.getElementById('tabelaAparelhos').querySelector('tbody');

function calcKwhMes(potW, horasDia, qtd){
  // kWh/mês = (W * horas/dia * dias * qtd) / 1000
  return (potW * horasDia * 30 * qtd) / 1000;
}
function addRow({nome, pot, horas, qtd, kwh}){
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>${nome}</td>
    <td>${pot}</td>
    <td>${horas}</td>
    <td>${qtd}</td>
    <td>${kwh.toFixed(1)}</td>
    <td><button class="remove">Remover</button></td>
  `;
  tabela.appendChild(tr);

  // remover
  tr.querySelector('.remove').addEventListener('click', () => {
    const idx = aparelhosLabels.indexOf(nome);
    if (idx > -1) {
      aparelhosLabels.splice(idx, 1);
      aparelhosData.splice(idx, 1);
      aparelhosChart.update();
    }
    tr.remove();
  });
}

form.addEventListener('submit', (e) => {
  e.preventDefault();
  const nome = document.getElementById('aparelhoNome').value.trim();
  const pot = parseFloat(document.getElementById('aparelhoPotencia').value);
  const horas = parseFloat(document.getElementById('aparelhoHoras').value);
  const qtd = parseInt(document.getElementById('aparelhoQtd').value);

  if (!nome || isNaN(pot) || isNaN(horas) || isNaN(qtd)) return;

  const kwh = calcKwhMes(pot, horas, qtd);
  addRow({nome, pot, horas, qtd, kwh});

  // atualiza gráfico
  aparelhosLabels.push(nome);
  aparelhosData.push(kwh);
  aparelhosChart.data.labels = [...aparelhosLabels];
  aparelhosChart.data.datasets[0].data = [...aparelhosData];
  aparelhosChart.update();

  // limpa
  form.reset();
  document.getElementById('aparelhoQtd').value = 1;
});

// =====================
// Micro interações
// =====================
const searchInput = document.getElementById('searchInput');
searchInput.addEventListener('focus', () => searchInput.parentElement.style.boxShadow = '0 10px 24px rgba(4,182,0,.18)');
searchInput.addEventListener('blur', () => searchInput.parentElement.style.boxShadow = 'var(--shadow)');
