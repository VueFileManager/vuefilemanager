<template>
    <div>
        <VueFolderIcon v-if="!item.data.attributes.isTeamFolder" />
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
