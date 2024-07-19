<script>
    import { RouterLink } from 'vue-router';

    import { useProfileStore } from '@/stores/profile';
    import { useCommonStore } from '@/stores/common';

    import { v4 as uuidv4 } from 'uuid';

    export default {
        components: {
            RouterLink,
        },
        methods: {
            load() {

            },

            save() {
                const profile = useProfileStore();

                /*
                if ( !this.inputs.id ) {
                    this.inputs.id = this.generate_client_id();
                }
                */

                this.save_to_storage();
            },

            save_to_storage() {
                let found = false;
                let storage = this.storage_control( 'get', false );

                this.storage_control( 'set', this.inputs );
            },

            load_from_storage( id, should_close ) {
                let storage = this.storage_control( 'get', false );

                if ( storage ) {
                    this.inputs = storage;

                    this.save();
                    this.$globalUtils.modal_close();
                }
            },

            storage_control( method, data ) {
                switch ( method ) {
                    case 'get' :
                        return JSON.parse( localStorage.getItem( this.storage_key ) );
                        break;

                    case 'set' :
                        localStorage.setItem( this.storage_key, JSON.stringify( data ) );
                        break;
                }

                return true;
            },

            clear_fields() {
                let key = null;

                for ( key in this.template ) {
                    this.inputs[ key ] = this.template[ key ];
                }
            },

            generate_client_id() {
                return uuidv4();
            }
        },
        created() {
            const profile = useProfileStore();
            const common = useCommonStore();

            let key = null;

            // save old template
            this.template = JSON.parse( JSON.stringify( this.inputs ) );

            for ( key in profile.settings ) {
                this.inputs[ key ] = profile.settings[ key ];
            }

            this.profile = this.storage_control( 'get', false );

            console.log( 'view', common.state.view );

            if ( common.state.view ) {
                switch ( common.state.view ) {
                    case 'new' :
                        this.clear_fields();
                        break;
                }
            }
        },
        data() {
            return {
                storage_key: 'alpha__profile',
                template: null,
                inputs: {
                    id: '',

                    name_first: '',
                    name_last: '',
                    gender: 'M',

                    data_access_type: 'local'
                }
            };
        }
    };
</script>
<template>
    <dialog class="alpha__modal">
        <div class="alpha__modal-bounds">
            <header class="alpha__modal-header">
                <h3>Your Profile Information <span class="alpha__modal-close" v-on:click="this.$globalUtils.modal_close()"><i class="fal fa-close" aria-hidden="true"></i></span></h3>
            </header>
            <section class="alpha__modal-body">
                <fieldset>
                    <legend>Personal Information</legend>
                    <div class="form">
                        <div class="form__row">
                            <div class="form__column">
                                <label for="profile__name-first">First Name</label>
                                <input type="text" id="profile__name-first" name="profile_name_first" placeholder="i.e., Benjamin" v-model="inputs.name_first">
                            </div>
                            <div class="form__column">
                                <label for="profile__name-last">Last Name</label>
                                <input type="text" id="profile__name-last" name="profile_name_last" placeholder="i.e., Franklin" v-model="inputs.name_last">
                            </div>
                            <div class="form__column">
                                <label>Gender</label>
                                <select id="owner__gender" name="owner_gender" v-model="inputs.gender">
                                    <option v-for="( option, option_index ) in this.$globalUtils.get_dataset_as_kvp( 'gender' )" v-bind:key="option.value" v-bind:value="option.value">{{ option.label }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Data Access</legend>
                    <div class="form">
                        <div class="form__row">
                            <div class="form__column">
                                <div class="form__buttons">
                                    <div class="form__buttons-column">
                                        <input type="radio" id="data__access-local" name="method" value="local" checked v-model="inputs.data_access_type">
                                        <label for="data__access-local">Local</label>
                                    </div>
                                    <div class="form__buttons-column">
                                        <input type="radio" id="data__access-master" name="method" value="remote-master" v-model="inputs.data_access_type">
                                        <label for="data__access-master">Remote Master</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </section>
            <footer class="alpha__modal-footer">
                <button type="button" v-on:click="save">Save and Close</button>
                <button type="button" class="btn-small btn-grayscale" v-on:click="this.$globalUtils.modal_close()">Close</button>
                <button type="button" class="btn-small btn-warn" v-on:click="this.clear_fields()">Clear</button>
            </footer>
        </div>
   </dialog>
</template>
