<template>
    <div class="label">
        <span :class="['label-dot', color]"></span>
        <b class="label-title">
            {{ title }}
        </b>
    </div>
</template>

<script>
export default {
    name: 'DotLabel',
    props: ['color', 'title'],
}
</script>

<style lang="scss" scoped>
.label {
    display: flex;
    align-items: center;

    .label-dot {
        margin-right: 10px;
        width: 8px;
        height: 8px;
        display: block;
        border-radius: 8px;
        flex: none;

        &.success {
            background: #0abb87;
        }

        &.danger {
            background: #fd397a;
        }

        &.warning {
            background: #ffb822;
        }

        &.info {
            background: #5578eb;
        }

        &.primary {
            background: red;
        }

        &.purple {
            background: #9d66fe;
        }

        &.secondary {
            background: #e1e1ef;
        }
    }

    .label-title {
        font-size: 16px;
        font-weight: 700;
    }
}
</style>
