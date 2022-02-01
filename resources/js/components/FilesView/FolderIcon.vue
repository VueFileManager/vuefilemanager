<template>
    <div>
        <VueFolderIcon v-if="!item.data.attributes.isTeamFolder" />
        <VueFolderTeamIcon v-if="item.data.attributes.isTeamFolder" style="width: 53px; height: 52px" />
    </div>
</template>

<script>
import VueFolderTeamIcon from './Icons/VueFolderTeamIcon'
import VueFolderIcon from './Icons/VueFolderIcon'

export default {
    name: 'FolderIcon',
    props: ['item'],
    components: {
        VueFolderTeamIcon,
        VueFolderIcon,
    },
}
</script>
