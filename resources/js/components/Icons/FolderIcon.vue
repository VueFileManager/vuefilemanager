<template>
    <div>
        <VueFolderIcon />
    </div>
</template>

<script>
import VueFolderIcon from './VueFolderIcon'

export default {
    name: 'FolderIcon',
    props: ['item'],
    components: {
        VueFolderIcon,
    },
}
</script>
