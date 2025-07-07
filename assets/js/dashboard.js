document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.lpmanagerDashboard === 'undefined') {
        console.error('lpmanagerDashboard data not found.');
        return;
    }

    fetch(window.lpmanagerDashboard.apiUrl, {
        method: 'GET',
        headers: {
            'X-WP-Nonce': window.lpmanagerDashboard.nonce
        }
    })
    .then(response => {
        if (!response.ok) throw new Error(`Network response was not ok: ${response.statusText}`);
        return response.json();
    })
    .then(data => {
        // Landing Page status chart
        new Chart(document.getElementById('landingPageChart'), {
            type: 'bar',
            data: {
                labels: ['Published', 'Draft', 'Pending'],
                datasets: [{
                    label: 'Landing Pages',
                    data: [
                        data.landing_pages.publish || 0,
                        data.landing_pages.draft || 0,
                        data.landing_pages.pending || 0
                    ],
                    backgroundColor: ['#3cba9f', '#f4c430', '#e74c3c']
                }]
            },
            options: { maintainAspectRatio: false }
        });

        // Visits per page chart
        new Chart(document.getElementById('visitsPerPageChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(data.visits_per_page || {}),
                datasets: [{
                    label: 'Visits per Page',
                    data: Object.values(data.visits_per_page || {}),
                    backgroundColor: '#2ecc71'
                }]
            },
            options: { maintainAspectRatio: false }
        });

        // Visits per keyword chart
        new Chart(document.getElementById('visitsPerKeywordChart'), {
            type: 'bar',
            data: {
                labels: data.keywords.map(k => k.label),
                datasets: [{
                    label: 'Landing Pages per Keyword',
                    data: data.keywords.map(k => k.count),
                    backgroundColor: '#9b59b6'
                }]
            },
            options: { maintainAspectRatio: false }
        });

        // Top Converters chart
        new Chart(document.getElementById('topConvertersChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(data.conversions_per_page || {}),
                datasets: [{
                    label: 'Conversions',
                    data: Object.values(data.conversions_per_page || {}),
                    backgroundColor: '#ff9800'
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });

        // Daily visits chart
        new Chart(document.getElementById('dailyVisitsChart'), {
            type: 'line',
            data: {
                labels: Object.keys(data.daily_visits || {}),
                datasets: [{
                    label: 'Daily Visits',
                    data: Object.values(data.daily_visits || {}),
                    backgroundColor: 'rgba(52, 152, 219, 0.2)',
                    borderColor: '#3498db',
                    fill: true
                }]
            },
            options: { maintainAspectRatio: false }
        });

        // Keyword distribution pie chart
        new Chart(document.getElementById('keywordChart'), {
            type: 'pie',
            data: {
                labels: data.keywords.map(k => k.label),
                datasets: [{
                    label: 'Landing Pages',
                    data: data.keywords.map(k => k.count),
                    backgroundColor: ['#2196f3', '#4caf50', '#ffc107', '#f44336', '#9c27b0', '#009688']
                }]
            },
            options: { maintainAspectRatio: false }
        });

    })
    .catch(error => {
        console.error('Fetch dashboard data failed:', error);
    });
});