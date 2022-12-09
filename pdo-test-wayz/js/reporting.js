let ctx = document.querySelector("#chartCanvas2");
let graph = new Chart(ctx, {
  type: "pie",
  data: {
    labels: ["Pro", "Perso"],
    datasets: [
      {
        label: ["Pro", "Perso"],
        data: [60, 40],
        backgroundColor: ["#6b5b95", "#757575"],
      },
    ],
    // {
    //     label: 'Nombre de "J\'aime"',
    //     data: [14, 2, 5, 8, 7, 22],
    //     backgroundColor: ['red', 'lightblue', 'lightyellow', 'lightgreen', 'pink', 'gold']
    // }]
  },
  options: {
    plugins: {
      title: {
        font: {
          size: 14,
          family: 'kronaone',
        },
        display: true,
        text: "Type d'événements",
        position: "top",
        color: "#121212",
        padding: {
          bottom: 30,
        },
      },
      legend: {
        display: true,
        position: "bottom",
        labels: {
          color: '#121212',
        },
        padding: {
          top: 300,
        },
      },
    },
  },
});

let ctx3 = document.querySelector("#chartCanvas4");
let graph3 = new Chart(ctx3, {
  type: "bar",
  data: {
    labels: [
      "Track 1",
      "Track 2",
      "Track 3",
      "Track 4",
      "Track 5",
      "Track 6",
    ],
    datasets: [
      {
        data: [140, 160, 120, 130, 160, 140],
        backgroundColor: [
          "#6b5b95",
          "#6b5b95",
          "#6b5b95",
          "#6b5b95",
          "#6b5b95",
          "#6b5b95",
        ],
      },
    ],
    // {
    //     label: 'Nombre de "J\'aime"',
    //     data: [14, 2, 5, 8, 7, 22],
    //     backgroundColor: ['red', 'lightblue', 'lightyellow', 'lightgreen', 'pink', 'gold']
    // }]
  },
  options: {
    plugins: {
      title: {
        font: {
          size: 14,
          family: 'kronaone',
        },
        display: true,
        text: "BPM",
        position: "top",
        color: "#121212",
        padding: {
          bottom: 30,
        },
      },
      legend: {
        display: false,
        position: "bottom",
        labels: {
          color: '#121212',
        },
      },
    },
    // scales: {
    //     yAxes: [{
    //         ticks: {
    //             beginAtZero: true
    //         }
    //     }]
    // }
  },
});

const percent = 80;
const color = "#212121";
const canvas = "chartCanvas";
const container = "chartContainer";

const percentValue = percent; // Sets the single percentage value
const colorwhite = color, // Sets the chart color
  animationTime = "1400"; // Sets speed/duration of the animation

const chartFigure = document.getElementById("chart__figure");
const chartCanvas = document.getElementById(canvas); // Sets canvas element by ID
const chartContainer = document.getElementById(container); // Sets container element ID

// const pPercent = document.createElement("p");
// pPercent.setAttribute("id", "pourcentage2");
// pPercent.innerText = percentValue + "%";

// chartFigure.appendChild(pPercent);

// Create a new Chart object
const doughnutChart = new Chart(chartCanvas, {
  type: "doughnut",
  data: {
    labels: [
      "Moi",
      "Collaborateurs",
    ],
    datasets: [
      {
        data: [12, 19],
        backgroundColor: [
          "#757575",
          "#6b5b95",
        ],
      },
    ],
    // {
    //     label: 'Nombre de "J\'aime"',
    //     data: [14, 2, 5, 8, 7, 22],
    //     backgroundColor: ['red', 'lightblue', 'lightyellow', 'lightgreen', 'pink', 'gold']
    // }]
  },

  options: {
    plugins: {
      title: {
        font: {
          size: 14,
          family: 'kronaone',
        },
        display: true,
        text: "Envois effectués",
        position: "top",
        color: "#121212",
        padding: {
          bottom: 30,
        },
      },
      legend: {
        display: true,
        position: "bottom",
        labels: {
          color: '#121212',
        }
      },
      cutoutPercentage: 80, // The percentage of the middle cut out of the chart
      responsive: false, // Set the chart to not be responsive
      tooltips: {
        enabled: true, // Hide tooltips
      },
    },
  },
});

Chart.defaults.global.animation.duration = animationTime; // Set the animation duration

divElement.innerHTML = domString; // Parse the HTML set in the domString to the innerHTML of the divElement
chartContainer.appendChild(divElement.firstChild); // Append the divElement within the chartContainer as it's child
