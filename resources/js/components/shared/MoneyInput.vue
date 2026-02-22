<script setup lang="ts">
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useMoney } from '@/composables/useMoney'
import { watch } from 'vue'

interface Props {
    id?: string
    label?: string
    placeholder?: string
    error?: string
    disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    id: 'money-input',
    placeholder: 'R$ 0,00',
})

const modelValue = defineModel<string>({ required: true })

const { numericValue, formattedValue, handleInput, reset } = useMoney()

watch(numericValue, (value) => {
    modelValue.value = value > 0 ? value.toFixed(2) : ''
})

watch(modelValue, (value) => {
    if (value === '') reset()
})
</script>

<template>
    <div class="space-y-2">
        <Label v-if="label" :for="id">{{ label }}</Label>
        <Input
            :id="id"
            type="text"
            inputmode="numeric"
            :placeholder="placeholder"
            :value="formattedValue"
            :disabled="disabled"
            :class="{ 'border-destructive': error }"
            @input="handleInput"
        />
        <p v-if="error" class="text-sm text-destructive">{{ error }}</p>
    </div>
</template>