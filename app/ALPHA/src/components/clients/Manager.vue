<script>
    import { RouterLink } from 'vue-router';

    export default {
        components: {
            RouterLink
        },
        data() {
            return {
            };
        }
    };
</script>
<template>
    <div class="alpha__modal">
        <div class="alpha__modal-bounds">
            <header class="alpha__modal-header">
                <h3>Add/Edit Client Information <span class="alpha__modal-close" v-on:click="this.$globalUtils.modal_close()"><i class="fal fa-close" aria-hidden="true"></i></span></h3>
            </header>
            <section class="alpha__modal-body">
                <fieldset>
                    <legend>Owner Information</legend>
                    <div class="form">
                        <div class="form__row">
                            <div class="form__column form__column--large">
                                <label>Initial Investment</label>
                                <input type="text" id="owner__investment" name="investment" class="money" placeholder="i.e., $100,000.00">
                            </div>
                        </div>
                        <div class="form__row">
                            <div class="form__column">
                                <label>First Name</label>
                                <input type="text" id="owner__name-first" name="name_first" placeholder="i.e., Benjamin">
                            </div>
                            <div class="form__column">
                                <label>Last Name</label>
                                <input type="text" id="owner__name-last" name="name_last" placeholder="i.e., Franklin">
                            </div>
                        </div>
                        <div class="form__row">
                            <div class="form__column">
                                <label>Birthdate <span class="badge">Age 65</span></label>
                                <input type="date" id="owner__birthdate" name="birthdate" placeholder="i.e., 08/12/1979">
                            </div>
                            <div class="form__column">
                                <label>State</label>
                                <select id="owner__state" name="state">
                                    <option v-for="( state, state_index ) in this.$globalUtils.get_dataset( 'states_usa' )" v-bind:key="state_index" v-bind:value="state">{{ this.$globalUtils.format( 'states_usa', state ) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Benefit Type</legend>
                    <div class="form">
                        <div class="form__row">
                            <div class="form__column">
                                <label>Method</label>
                                <div class="form__buttons">
                                    <div class="form__buttons-column">
                                        <input type="radio" id="owner__method-single" name="method" value="S" checked>
                                        <label for="owner__method-single">Single</label>
                                    </div>
                                    <div class="form__buttons-column">
                                        <input type="radio" id="owner__method-joint" name="method" value="J">
                                        <label for="owner__method-joint">Joint</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Joint Information</legend>

                    <div class="form">
                        <div class="form__row">
                            <div class="form__column">
                                <label for="joint__name-first">First Name</label>
                                <input type="text" id="joint__name-first" name="joint_first" placeholder="i.e., Deborah">
                            </div>
                            <div class="form__column">
                                <label for="joint__name-last">Last Name</label>
                                <input type="text" id="joint__name-last" name="joint_last" placeholder="i.e., Read">
                            </div>
                        </div>
                        <div class="form__row">
                            <div class="form__column">
                                <label>Birthdate <span class="badge">Age 65</span></label>
                                <input type="date" id="joint__birthdate" name="joint_birthdate" placeholder="i.e., 08/12/1979">
                            </div>
                            <div class="form__column">
                                <label>State</label>
                                <select id="joint__state" name="joint_state">
                                    <option v-for="( state, state_index ) in this.$globalUtils.get_dataset( 'states_usa' )" v-bind:key="state_index" v-bind:value="state">{{ this.$globalUtils.format( 'states_usa', state ) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </section>
            <footer class="alpha__modal-footer">
                <button type="button">Save and Close</button>
                <button type="button" class="btn-warn">Close</button>
            </footer>
        </div>
    </div>
</template>
