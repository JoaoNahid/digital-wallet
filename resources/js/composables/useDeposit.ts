import type { DepositFormData } from '@/types/wallet'
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

export function useDeposit() {
    const form = useForm<DepositFormData>({
        amount: '',
        description: '',
    })

    const isValid = computed(() => {
        const amount = parseFloat(form.amount)
        return !isNaN(amount) && amount > 0
    })

    const formattedAmount = computed(() => {
        const amount = parseFloat(form.amount)
        if (isNaN(amount)) return ''
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        }).format(amount)
    })

    function submit(onSuccess?: () => void) {
        form.post('/wallet/deposit', {
            preserveScroll: true,
            onSuccess: () => {
                form.reset()
                onSuccess?.()
            },
        })
    }

    function reset() {
        form.reset()
        form.clearErrors()
    }

    return {
        form,
        isValid,
        formattedAmount,
        submit,
        reset,
    }
}