<script>
import { useSetsStore } from '@/stores/sets';
export default {
    components: {
    },
    created() {
        this.$emitter.on( 'alpha__init-status', this.update_status );
    },
    methods: {
        update_status( payload ) {
            this.stage.current = payload.stage.current;
            this.stage.total = payload.stage.total;
            this.stage.data.identifier = payload.stage.data.identifier;
            this.stage.data.text = payload.stage.data.text;

            switch ( this.stage.data.identifier ) {
                case 'complete' :
                    console.log( 'initialization complete' );

                    this.initialized = true;
                    break;
            }
        }
    },
    data() {
        return {
            initialized: false,
            stage: {
                current: 1,
                total: 1,
                data: {
                    identifier: '',
                    text: 'Initializing...'
                }
            }
        };
    }
};
</script>
<template>
  <div class="initialization" v-bind:class="{ 'initialization--complete': initialized }">
      <div class="initialization__inner">
          <div class="initialization__logo"><div class="title">ALPHA</div> by Annuity Association</div>
          <div class="initialization__indicator"></div>
          <div class="initialization__stage">
              Stage <em>{{ stage.current }}</em> of <em>{{ stage.total }}</em>
          </div>
          <div class="initialization__text">{{ stage.data.text }}</div>
      </div>
  </div>
</template>


