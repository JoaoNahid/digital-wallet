import { computed, ref, watch, type Ref } from 'vue'

export function useMoney(initialValue: string | number = '') {
    const rawValue = ref(String(initialValue))

    const numericValue = computed(() => {
        const cleaned = rawValue.value.replace(/[^\d]/g, '')
        return parseInt(cleaned, 10) / 100 || 0
    })

    const formattedValue = computed(() => {
        if (numericValue.value === 0 && rawValue.value === '') return ''
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        }).format(numericValue.value)
    })

    const displayValue = computed(() => {
        if (numericValue.value === 0 && rawValue.value === '') return ''
        return numericValue.value.toFixed(2)
    })

    function handleInput(event: Event) {
        const input = event.target as HTMLInputElement
        const value = input.value.replace(/[^\d]/g, '')
        rawValue.value = value
    }

    function reset() {
        rawValue.value = ''
    }

    return {
        rawValue,
        numericValue,
        formattedValue,
        displayValue,
        handleInput,
        reset,
    }
}