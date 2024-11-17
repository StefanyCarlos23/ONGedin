document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("contribuinteChart").getContext("2d");
    const contribuinteChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["Voluntários", "Doadores", "Ambos"],
            datasets: [{
                label: "Total de Contribuintes",
                data: [150, 100, 148],
                backgroundColor: ["#7cbdb7", "#4da6a1", "#85d7d1"]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});