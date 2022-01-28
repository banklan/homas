<script>
import { Doughnut } from 'vue-chartjs'

export default{
    extends: Doughnut,
    props: {
        chartData:{
            type: Array | Object,
            default: null,
            required: false
        },
        chartLabels:{
            type: Array,
            required: true
        },
    },
    data() {
        return {
            options:{
                scales:{
                    yAxes:[{
                        ticks:{
                            beginAtZero: true,
                            min: 0,
                            max: 10,
                            stepSize: 5
                        },
                        gridLines:{
                            display: true
                        }
                    }],
                    xAxes:[{
                        gridLines:{
                            display: false
                        }
                    }]
                },
                legend:{
                    display: true
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        }
    },
    mounted(){
        this.renderChart({
            labels: this.chartLabels,
            datasets: [{
                label: 'Bar Chart',
                borderColor: '#FF5200',
                pointBackgroundColor: '#08F7EB',
                borderWidth: 1,
                pointBorderColor: '#2554ff',
                backgroundColor: [
                    'red', 'green', 'purple', 'violet'
                ],
                borderColor: [
                    'red', 'green', 'purple', 'violet'
                ],
                hoverBackgroundColor: [
                    'orange', 'blue', 'violet'
                ],
                hoverBorderColor: ['orange', 'blue', 'violet'],
                data: this.chartData
            }]
        }, this.options)
    }
}
</script>

