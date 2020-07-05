<template>
    <div class="plans-wrapper" v-if="plans">
        <article class="plan" v-for="(plan, i) in plans" :key="i">
            <div class="plan-wrapper">
                <header class="plan-header">
                    <div class="icon">
                        <hard-drive-icon size="26"></hard-drive-icon>
                    </div>
                    <h1 class="title">{{ plan.data.attributes.name }}</h1>
                    <h2 class="description">{{ plan.data.attributes.description }}</h2>
                </header>
                <section class="plan-features">
                    <b class="storage-size">{{ plan.data.attributes.capacity_formatted }}</b>
                    <span class="storage-description">Of Storage Capacity</span>
                </section>
                <footer class="plan-footer">
                    <b class="price">
                        {{ plan.data.attributes.price }}/Mo.
                    </b>
                    <ButtonBase @click.native="selectPlan(plan)" type="submit" button-style="secondary" class="sign-in-button">
                        Sign Up
                    </ButtonBase>
                </footer>
            </div>
        </article>
    </div>
</template>

<script>
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import {HardDriveIcon} from "vue-feather-icons"
    import axios from 'axios'

    export default {
        name: 'PlanPricingTables',
        components: {
            HardDriveIcon,
            ButtonBase,
        },
        data() {
            return {
                plans: undefined,
            }
        },
        methods: {
            selectPlan(plan) {
                this.$emit('selected-plan', plan)
                this.$router.push({name: 'UpgradeBilling'})
            }
        },
        created() {

            axios.get('/api/public/pricing')
                .then(response => {
                    this.plans = response.data
                    this.$emit('load', false)
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .plan {
        text-align: center;
        flex: 0 0 33%;
        padding: 0 25px;
        margin-bottom: 45px;

        .plan-wrapper {
            box-shadow: 0 7px 20px 5px hsla(220, 36%, 16%, 0.03);
            padding: 25px;
            border-radius: 8px;
            @include transition;

            &:hover {
                @include transform(translateY(-20px) scale(1.05));
                box-shadow: 0 15px 25px 5px hsla(220, 36%, 16%, 0.08);
            }
        }

        .plan-header {

            .icon {
                path, line, polyline, rect, circle {
                    color: $theme;
                }
            }

            .title {
                @include font-size(22);
                font-weight: 800;
            }

            .description {
                @include font-size(14);
                font-weight: 600;
            }
        }

        .plan-features {
            margin: 65px 0;

            .storage-size {
                @include font-size(48);
                font-weight: 900;
                line-height: 1.1;
            }

            .storage-description {
                display: block;
                @include font-size(15);
                font-weight: 800;
            }
        }

        .plan-footer {

            .sign-in-button {
                width: 100%;
                text-align: center;
            }

            .price {
                color: $theme;
                @include font-size(18);
                display: block;
                margin-bottom: 20px;
            }
        }
    }

    .plans-wrapper {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -25px;
        justify-content: center;
    }

    @media only screen and (max-width: 1024px) {

    }

    @media (prefers-color-scheme: dark) {

    }
</style>
