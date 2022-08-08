<template>
    <div class="shrink-0 grow-0">
        <img
            :style="{ width: size + 'px', height: size + 'px' }"
            v-if="member.data.attributes.avatar"
            :src="avatar"
            :class="[
                borderRadius,
                {
                    'border-3 border-white dark:border-dark-background': isBorder,
                },
            ]"
            class="object-cover mx-auto"
        />
        <div
            v-else
            class="flex items-center justify-center mx-auto"
            :class="[
                borderRadius,
                {
                    'border-3 border-white dark:border-dark-background': isBorder,
                    'dark:bg-4x-dark-foreground bg-light-background': !member.data.attributes.color,
                },
            ]"
            :style="{
                width: size + 'px',
                height: size + 'px',
                background: member.data.attributes.color ? member.data.attributes.color : '',
            }"
        >
            <span class="font-extrabold uppercase text-white dark:text-gray-900"
				:class="[fontSize, {'!text-gray-900': !member.data.attributes.color}]"
			>
                {{ letter }}
            </span>
        </div>
    </div>
</template>
<script>
export default {
    name: 'MemberAvatar',
    props: ['isBorder', 'member', 'size'],
    computed: {
        letter() {
            let string = this.member.data.attributes.name
                ? this.member.data.attributes.name
                : this.member.data.attributes.email

            return string.substr(0, 1)
        },
        borderRadius() {
            return this.size > 32 ? 'rounded-xl' : 'rounded-lg'
        },
        fontSize() {
            if (this.size > 42) {
                return 'text-lg'
            } else if (this.size > 32) {
                return 'text-base'
            } else {
                return 'text-sm'
            }
        },
        avatar() {
            if (this.size >= 52) {
                return this.member.data.attributes.avatar.md
            } else if (this.size > 32) {
                return this.member.data.attributes.avatar.sm
            } else {
                return this.member.data.attributes.avatar.xs
            }
        },
    },
}
</script>
