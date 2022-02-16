<template>
    <video
        :src="file.data.attributes.file_url"
        class="video"
        :class="{ 'file-shadow': !$isMobile() }"
        controlsList="nodownload"
        disablePictureInPicture
        playsinline
        controls
    />
</template>

<script>
export default {
    name: 'Video',
    props: ['file'],
}
</script>
