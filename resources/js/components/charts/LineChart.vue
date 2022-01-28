<script>
import { Line } from 'vue-chartjs'

export default {
    extends: Line,
    data() {
        return {
            chartData:{
                // labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'Jul', 'Aug', 'Sept'],
                // labels: [],
                datasets: [
                    {
                        label: 'Services created Chart',
                        // data: [600, 500, 1150, 800, 750, 1200, 1100, 680, 800],
                        // data: [],
                        fill: true,
                        borderColor: '#fff',
                        backgroundColor: '#fff',
                        borderWidth: 2
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                legend: {
                    display: true
                },
                responsive: true,
                maintainAspectRatio: false
            }
        }
    },
    computed:{
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
    },
    methods:{
        getLastFiveWeeksData(){
            axios.get(this.api + '/auth-admin/get_last_weeks_services', this.adminHeaders).then((res) => {
                let weeks = res.data.map(a => a.week_starting)
                let counts = res.data.map(a => a.services_count)
                this.chartData.labels = weeks
                this.chartData.datasets.data = counts
                console.log(this.chartData.datasets.data)

                // let labels = res.data.reduce((acc, item) => {
                //     const [key, value] = item.split(': ')
                //     acc[key] = value
                //     return acc
                //     // for(let key in item){
                    //     if(item[key] !== null){
                    //         acc.push(key)
                    //     }
                    // }
                    // return acc;
                // }, {})
                // console.log(labels)
            })
        }
    },
    created() {
        // console.log('datasets', this.chartData.datasets[0].data)
    },
    mounted(){
        this.getLastFiveWeeksData()
        this.renderChart(this.chartData, this.options)
    }
}
</script>
