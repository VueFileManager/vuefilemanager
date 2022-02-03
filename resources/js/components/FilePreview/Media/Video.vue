<template>
    <div class="max-w-[1080px] max-h-full">
        <video
            :src="file.data.attributes.file_url"
            class="video"
            :class="{ 'file-shadow': !$isMobile() }"
            controlsList="nodownload"
            disablePictureInPicture
            playsinline
            controls
        />
    </div>
</template>

<script>
export default {
    name: 'Video',
    props: ['file'],
}
</script>
