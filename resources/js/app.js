import "../css/app.css";

import Alpine from "alpinejs";
import Chart from "chart.js/auto";

import "./navbar";
import "./auth";
import "./otp";
import "./auth-psycho";
import "./step3";
import "./step4";
import "./step5";

document.addEventListener("DOMContentLoaded", () => {
    window.Alpine = Alpine;
    window.Chart = Chart;
    Alpine.start();
});
