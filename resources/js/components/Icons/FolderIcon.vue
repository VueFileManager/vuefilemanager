<template>
    <div>
        <VueFolderIcon v-if="!item.data.attributes.isTeamFolder" />
        <VueFolderTeamIcon v-if="item.data.attributes.isTeamFolder" style="width: 53px; height: 52px" />
    </div>
</template>

<script>
import VueFolderTeamIcon from './VueFolderTeamIcon.vue'
import VueFolderIcon from './VueFolderIcon.vue'

export default {
    name: 'FolderIcon',
    props: ['item'],
    components: {
        VueFolderTeamIcon,
        VueFolderIcon,
    },
}
</script>
