<template>
  <div class="iCountUp">
    <i class='material-icons'>{{icon}}</i>
    <ICountUp
      :startVal="startVal"
      :endVal="endVal"
      :decimals="decimals"
      :duration="duration"
      :options="options"
      @ready="onReady"
    />
  </div>
</template>

<script type="text/babel">
  import ICountUp from 'vue-countup-v2';
  export default {
    name: 'demo',
    components: {
      ICountUp
    },
    props : {
      start : Number,
      end : Number,
      icon : String
    },
    data() {
      return {
        startVal: this.start,
        endVal: this.end,
        decimals: 0,
        duration: 5,
        options: {
          useEasing: true,
          useGrouping: true,
          separator: ',',
          decimal: '.',
          prefix: '',
          suffix: ''
        }
      };
    },
    methods: {
      onReady: function(instance, CountUp) {
        const that = this;
        // instance.update(that.endVal + 100);
      }
    }
  };
</script>

<style scoped>
  .iCountUp {
    font-size: 2em;
    margin: 0;
    color: #4d63bc;
  }
</style>