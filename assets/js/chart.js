jQuery(document).ready(function($) {

    var statusData = window.status_data;
    var paymentMethodData = window.payment_method_data;

    renderChart('status-chart', 'Order Status', statusData, 'rgba(255, 99, 132, 0.2)', 'rgba(255, 99, 132, 1)');
    renderChart('payment-method-chart', 'Payment Method', paymentMethodData, 'rgba(75, 192, 192, 0.2)', 'rgba(75, 192, 192, 1)');

    // Function to render a chart
    function renderChart(canvasId, label, data, bgColor, borderColor) {
        var ctx = document.getElementById(canvasId).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: label,
                    data: data.counts,
                    backgroundColor: bgColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
