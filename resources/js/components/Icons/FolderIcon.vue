<template>
    <div>
        <VueFolderIcon v-if="!item.data.attributes.isTeamFolder" />
        <VueFolderTeamIcon v-if="item.data.attributes.isTeamFolder" style="width: 53px; height: 52px" />
    </div>
</template>

<script>
import VueFolderTeamIcon from './VueFolderTeamIcon'
import VueFolderIcon from './VueFolderIcon'

export default {
    name: 'FolderIcon',
    props: ['item'],
    components: {
        VueFolderTeamIcon,
        VueFolderIcon,
    },
}
</script>
