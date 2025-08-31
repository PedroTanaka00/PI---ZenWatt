// Gráfico de Barras
new Chart(document.getElementById('barChart'), {
  type: 'bar',
  data: {
    labels: ["Jan", "Fev", "Mar", "Abr", "Mai"],
    datasets: [{
      label: "Consumo",
      data: [12, 19, 7, 15, 10],
      backgroundColor: ["#04b600", "#019400", "#026400", "#038a00", "#014700"]
    }]
  }
});

// Gráfico de Linhas
new Chart(document.getElementById('lineChart'), {
  type: 'line',
  data: {
    labels: ["Abr", "Mai", "Jun", "Jul", "Ago", "Set"],
    datasets: [{
      label: "Energia",
      data: [50, 100, 75, 150, 120, 180],
      borderColor: "#04b600",
      fill: false,
      tension: 0.3
    },
    {
      label: "Custo",
      data: [30, 80, 60, 110, 90, 140],
      borderColor: "#eeff00ff",
      fill: false,
      tension: 0.3
    }]
  }
});

// Gráfico de Rosca
new Chart(document.getElementById('doughnutChart'), {
  type: 'doughnut',
  data: {
    labels: ["Pessoal", "Negócios"],
    datasets: [{
      data: [60, 40],
      backgroundColor: ["#04b600", "#eeff00ff"]
    }]
  }
});

// -----------------
// Modal do calendário
// -----------------
const days = document.querySelectorAll(".calendar-grid .day");
const modal = document.getElementById("calendarModal");
const closeModal = document.getElementById("closeModal");

days.forEach(day => {
  day.addEventListener("click", () => {
    modal.style.display = "flex";
  });
});

closeModal.addEventListener("click", () => {
  modal.style.display = "none";
});

window.addEventListener("click", (e) => {
  if (e.target === modal) {
    modal.style.display = "none";
  }
});
