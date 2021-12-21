<template>
    <section class="content-sidebar flex-none xl:w-56 w-52 lg:block hidden overflow-y-auto pt-6 select-none dark:bg-dark-background bg-light-background" id="content-sidebar">
        <slot></slot>
    </section>
</template>

<script>
    export default {
        name: 'ContentSidebar',
    }
</script>

<style scoped lang="scss">
    .content-sidebar {
		//background: linear-gradient(0deg, rgba(246, 245, 241, 0.4) 0%, rgba(243, 244, 246, 0.4) 100%);
	}
</style>
