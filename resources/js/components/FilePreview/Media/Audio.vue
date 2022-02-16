<template>
    <audio
        :class="{ 'file-shadow': !$isMobile() }"
        class="file audio"
        :src="file.data.attributes.file_url"
        controls
    ></audio>
</template>

<script>
export default {
    name: 'Audio',
    props: ['file'],
}
</script>
