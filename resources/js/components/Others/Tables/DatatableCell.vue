<template>
    <tr class="table-row">
        <td class="table-cell" v-for="(collumn, index) in normalizedColumns" :key="index">
            <span>{{ collumn }}</span>
        </td>
    </tr>
</template>

<script>
export default {
    props: ['data'],
    computed: {
        normalizedColumns() {
            // Remove ID from object
            if (this.data['id']) delete this.data['id']

            // Return object
            return Object.values(this.data)
        },
    },
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.table-row {
    border-radius: 8px;

    &:hover {
        background: $light_background;
    }

    .table-cell {
        padding-top: 15px;
        padding-bottom: 15px;

        &:first-child {
            padding-left: 15px;
        }

        &:last-child {
            padding-right: 15px;
            text-align: right;
        }

        span {
            @include font-size(16);
            font-weight: bold;
        }
    }
}
</style>
