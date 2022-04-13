<template>
    <div
        class="block cursor-pointer select-none rounded-lg py-3 px-4"
        :class="{
            'bg-light-background dark:bg-2x-dark-foreground': isSelected,
        }"
    >
        <div class="mb-1.5 flex items-center justify-between">
            <CheckBox :is-clicked="isSelected" />
            <b class="flex-1 pl-4 text-left text-lg">
                {{ plan.data.attributes.name }}
            </b>
            <span
                class="text-theme bg-theme-100 ml-9 inline-block whitespace-nowrap rounded-xl py-1 px-2 text-sm font-extrabold"
            >
                {{ plan.data.attributes.price }} /
                {{ $t(`interval.${plan.data.attributes.interval}`) }}
            </span>
        </div>
        <ul class="ml-9 mb-3">
            <li class="mb-1.5 flex items-center" v-for="(value, key, i) in plan.data.attributes.features" :key="i">
                <CheckIcon size="12" class="svg-stroke-theme" />
                <small class="pl-1.5 text-xs font-bold text-gray-600" v-if="value !== -1">
                    {{ $t(key === 'max_team_members' ? 'max_team_members_total' : key, { value: value }) }}
                </small>
                <small class="pl-1.5 text-xs font-bold text-gray-600" v-if="value === -1">
                    {{ $t(`${key}.unlimited`) }}
                </small>
            </li>
        </ul>
    </div>
</template>
<script>
import { CheckIcon } from 'vue-feather-icons'
import CheckBox from '../Inputs/CheckBox'

export default {
    name: 'PlanDetail',
    components: {
        CheckIcon,
        CheckBox,
    },
    props: ['isSelected', 'plan'],
}
</script>
