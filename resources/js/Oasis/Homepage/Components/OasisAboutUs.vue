<template>
    <div class="oasis-about-us container">
        <div class="title-wrapper container">
            <h3 class="main-title">
                Kto Jsme?
            </h3>
            <h4 class="sub-title-sm">
                Jsme česká společnost, která pro fyzické a právnické osoby (živnostníky a firmy) poskytuje cloudové uložiště dle evropské směrnice (GDPR) a následného zákona o zpracování osobních údajů (zákon 110/2019 sb.).
            </h4>
        </div>
        <ul class="info-list">
            <li v-for="(item, i) in infoList" :key="i" class="info-list-item">
                <check-icon size="26" class="icon" />
                <p class="description">{{ item }}</p>
            </li>
        </ul>
        <router-link :to="{name: 'SignUp'}" class="base-button theme-color">
            Vyzkouset OasisDrive
        </router-link>
    </div>
</template>

<script>
    import {CheckIcon} from 'vue-feather-icons'

    export default {
        name: 'OasisAboutUs',
        components: {
            CheckIcon,
        },
        data() {
            return {
                infoList: [
                    'Zakládáme si na kvalitě a nejlepší možné bezpečnosti pro uchovávání a zpracování dat a dokumentů.',
                    'Spolupracujeme rovněž se specialisty v oboru práva a IT.',
                    'Důležitá je pro nás uživatelská jednoduchost, stoprocentní funkčnost a přehlednost.',
                    'Bezpečnost uložení dat tak, jak nařizuje směrnice EU (GDPR) a zákon o zpracování osobních údajů.',
                ]
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';
</style>
