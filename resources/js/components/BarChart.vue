<script>
import { Bar } from 'vue-chartjs'
export default {
  extends: Bar,
  props : ['docu_count_url', 'department_list_url'],
  data : () => {
    return {
      options : {
        responsive: true, 
        maintainAspectRatio: false,
      }
    }
  },
  mounted () {
    let Departments = []
    let docuCreatedCount = []
    axios.get(this.department_list_url)
    .then(response =>{
      let dept_data = response.data
      dept_data.forEach(e => {
        Departments.push(e.acronym)
        docuCreatedCount.push(0)
      });
    })
    .catch(e => {
      consoel.log(e);
    });
    // let Departments = [
    //   'AD', 'DEDFA', 'BoC', 'DD', 'ER', 'EMD', 'ES', 'FD' ,'MISD', 'DDDO', 
    //   'PD', 'PMO', 'PI', 'RDFCD'
    // ];
    // let docuCreatedCount = [0,0,0,0,0,0,0,0,0,0,0,0,0,0];
    try{
      axios
      .get(this.docu_count_url)
      .then(response => {
        let data = response.data
        if(data){
          data.forEach(element => {
            docuCreatedCount[element.department_id - 1] = element.record_count
          });
          this.renderChart({
            labels : Departments,
            datasets : [{
              label: "Documents recorded by the said department",
              backgroundColor : 'blue',
              data: docuCreatedCount
            }]   
          }, this.options)
        }
        else{
          alert('Error : Cannot get the api data for bar graph')
        }
      })
    }
    catch(e){
      console.log(e)
    }
  }
}
</script>