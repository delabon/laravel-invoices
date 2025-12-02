<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'
import { computed } from 'vue'

type SelectOption = { value: string | number; label: string }

const props = defineProps<{
    defaultValue?: string | number
    modelValue?: string | number
    class?: HTMLAttributes['class']
    options?: SelectOption[] | Record<string, string>
}>()

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
})

const normalizedOptions = computed(() => {
    if (!props.options) return []

    if (Array.isArray(props.options)) {
        return props.options
    }

    return Object.entries(props.options).map(([value, label]) => ({
        value,
        label,
    }))
})
</script>

<template>
    <select
        v-model="modelValue"
        data-slot="select"
        :class="cn(
            'border-input text-foreground placeholder:text-muted-foreground dark:bg-input/30 flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
            'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
            'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
            props.class,
        )"
    >
        <template v-if="normalizedOptions.length > 0">
            <option
                v-for="option in normalizedOptions"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </template>
        <slot v-else />
    </select>
</template>
