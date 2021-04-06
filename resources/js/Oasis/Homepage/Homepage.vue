<template>
    <div id="page">
        <OasisNavigation/>
        <OasisHeader/>
        <OasisFeatures/>
        <OasisPricing/>
        <OasisAboutUs/>
        <OasisContact/>
        <OasisFooter/>
    </div>
</template>

<script>
    import OasisNavigation from '@/Oasis/Homepage/Components/OasisNavigation'
    import OasisFeatures from '@/Oasis/Homepage/Components/OasisFeatures'
    import OasisPricing from '@/Oasis/Homepage/Components/OasisPricing'
    import OasisAboutUs from '@/Oasis/Homepage/Components/OasisAboutUs'
    import OasisContact from '@/Oasis/Homepage/Components/OasisContact'
    import OasisHeader from '@/Oasis/Homepage/Components/OasisHeader'
    import OasisFooter from '@/Oasis/Homepage/Components/OasisFooter'

    export default {
        name: 'Homepage',
        components: {
            OasisNavigation,
            OasisFeatures,
            OasisPricing,
            OasisAboutUs,
            OasisContact,
            OasisHeader,
            OasisFooter,
        },
    }
</script>

<style lang="scss">
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';

    @media (prefers-color-scheme: dark) {

        #page {
            background: darken($theme-bg-dark, 10%);
        }
    }
</style>
