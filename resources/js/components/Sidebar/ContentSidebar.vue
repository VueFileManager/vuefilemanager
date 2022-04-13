<template>
    <section
        class="content-sidebar z-[9] hidden w-52 flex-none select-none overflow-y-auto bg-light-background pt-6 dark:bg-dark-background lg:block xl:w-56"
        id="content-sidebar"
    >
        <slot />
    </section>
</template>

<script>
export default {
    name: 'ContentSidebar',
}
</script>
