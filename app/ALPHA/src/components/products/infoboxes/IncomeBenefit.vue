<template>
    <div class="benefit" v-if="profile">
        <input type="checkbox" name="benefit-ib-toggle" id="benefit-ib-toggle" value="1" class="benefit__toggle hidden" v-bind:checked="expanded">

        <h3 class="benefit__header">
            <label for="benefit-ib-toggle">
                Income Benefit Profile

                <span class="benefit__controls">
                    <i class="fal fa-chevron-double-up" aria-hidden="true" data-type="collapse"></i>
                    <i class="fal fa-chevron-double-down" aria-hidden="true" data-type="expand"></i>
                </span>
            </label>
        </h3>

        <div class="benefit__details">
            <fieldset class="benefit__details-group" data-group-type="extended">
                <legend>Profile Details</legend>

                <ul class="benefit__details-group-items">
                    <li class="benefit__details-group-item" data-item-label="Name">
                        {{ profile.name }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Type">
                        {{ this.$globalUtils.format( 'death_benefit_type', profile.death_benefit_type ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Premium Inheritance">
                        {{ this.$globalUtils.format( 'premium_inheritance', profile.premium_inheritance ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Age Rule Basis">
                        {{ this.$globalUtils.format( 'age_rule_basis', profile.age_rule_basis ) }}
                    </li>
                </ul>
            </fieldset>
            <fieldset class="benefit__details-group" data-group-type="extended">
                <legend>Extended Information</legend>

                <ul class="benefit__details-group-items">
                    <li class="benefit__details-group-item" data-item-label="Roll-Ups">
                        &mdash;
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Step-Ups">
                        &mdash;
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Issue Age Inheritance">
                        {{ this.$globalUtils.format( 'issue_age_inheritance', profile.issue_age_inheritance ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Enhancements">
                        &mdash;
                    </li>
                </ul>
            </fieldset>
            <fieldset class="benefit__details-group" data-group-type="issue-age">
                <legend>Joint Issuance</legend>

                <ul class="benefit__details-group-items">
                    <li class="benefit__details-group-item" data-item-label="Available?">
                        {{ this.$globalUtils.format( 'yesno', profile.issue_age_joint_availability ) }}
                    </li>
                </ul>

                <ul class="benefit__details-group-items" data-group-label="Owner">
                    <li class="benefit__details-group-item" data-item-label="Has Min Age?">
                        {{ this.$globalUtils.format( 'yesno', profile.issue_age_joint_owner_rules_min_age ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Has Max Age?">
                        {{ this.$globalUtils.format( 'yesno', profile.issue_age_joint_owner_rules_max_age ) }}
                    </li>
                </ul>

                <ul class="benefit__details-group-items" data-group-label="Annuitant">
                    <li class="benefit__details-group-item" data-item-label="Has Min Age?">
                        {{ this.$globalUtils.format( 'yesno', profile.issue_age_joint_annuitant_rules_min_age ) }}
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Has Max Age?">
                        {{ this.$globalUtils.format( 'yesno', profile.issue_age_joint_annuitant_rules_max_age ) }}
                    </li>
                </ul>
            </fieldset>
            <fieldset class="benefit__details-group" data-group-type="rider-fees">
                <legend>Rider Fees</legend>

                <ul class="benefit__details-group-items">
                    <li class="benefit__details-group-item" data-item-label="Current">
                        &mdash;
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Min">
                        &mdash;
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Max">
                        &mdash;
                    </li>
                </ul>
            </fieldset>
            <fieldset class="benefit__details-group" data-group-type="premiums">
                <legend>Premiums</legend>

                <ul class="benefit__details-group-items">
                    <li class="benefit__details-group-item" data-item-label="Initial">
                        &mdash;
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Max">
                        &mdash;
                    </li>
                </ul>
            </fieldset>
            <fieldset class="benefit__details-group" data-group-type="crediting">
                <legend>Interest Crediting</legend>

                <ul class="benefit__details-group-items">
                    <li class="benefit__details-group-item" data-item-label="Base">
                        &mdash;
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Multiplier">
                        &mdash;
                    </li>
                    <li class="benefit__details-group-item" data-item-label="Bonus">
                        &mdash;
                    </li>
                </ul>
            </fieldset>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        profile: null,
        expanded: false
    },
    created() {
        console.log( 'death benefit', this.profile );
    },
    data() {
        return {
        };
    }
};
</script>