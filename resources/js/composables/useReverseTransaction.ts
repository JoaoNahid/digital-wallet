import type { Transaction, ReverseFormData } from '@/types/wallet'
import { useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

export function useReverseTransaction() {
    const isOpen = ref(false)
    const transaction = ref<Transaction | null>(null)

    const form = useForm<ReverseFormData>({
        reason: '',
    })

    function openModal(t: Transaction) {
        transaction.value = t
        isOpen.value = true
    }

    function closeModal() {
        isOpen.value = false
        transaction.value = null
        form.reset()
        form.clearErrors()
    }

    function confirm() {
        if (!transaction.value) return

        form.post(`/transactions/${transaction.value.id}/reverse`, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal()
            },
        })
    }

    const isValid = computed(() => form.reason.trim().length > 0)

    return {
        isOpen,
        transaction,
        form,
        isValid,
        openModal,
        closeModal,
        confirm,
    }
}